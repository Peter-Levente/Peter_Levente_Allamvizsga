<?php

namespace App\Http\Controllers;

use App\Events\ProductAddedToCart;
use App\Services\UserInterestProfileUpdater;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;

// A kosár kezeléséért felelős kontroller: megtekintés, hozzáadás, frissítés, törlés
class CartController extends Controller
{
    /**
     * Kosár tartalmának megjelenítése, ajánlott termékekkel
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Régi kosárelemek törlése (1 napnál idősebbek)
        $this->cleanupUserCart();

        $userId = auth()->id();

        // A felhasználó kosarában lévő termékek lekérése a kapcsolt termékekkel együtt
        $cartItems = Cart::with('product')->where('user_id', $userId)->get();
        $productIds = $cartItems->pluck('product_id')->toArray();

        // Teljes ár kiszámítása
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $recommendedProducts = collect();

        // Ha van termék a kosárban, kezdjük el az ajánlórendszert
        if ($cartItems->isNotEmpty()) {
            $originalCategories = $cartItems->pluck('product.category')->unique()->toArray();

            // Kiválasztunk véletlenszerűen termékeket, amik nem szerepelnek a kosárban és más a kategóriájúak
            $candidates = Product::whereNotIn('id', $productIds)
                ->whereNotIn('category', $originalCategories)
                ->inRandomOrder()
                ->limit(100)
                ->get();

            // Kategóriánként csoportosítjuk a termékeket
            $groupedByCategory = $candidates->groupBy('category');

            // Minden kategóriából véletlenszerűen egy terméket ajánlunk
            foreach ($groupedByCategory as $productsInCategory) {
                if ($productsInCategory->isNotEmpty()) {
                    $recommendedProducts->push($productsInCategory->random());
                }
            }

            // Ha még nincs 4 ajánlott termék, akkor véletlenszerűen kiegészítjük
            if ($recommendedProducts->count() < 4) {
                $missing = 4 - $recommendedProducts->count();

                $additionalProducts = Product::whereNotIn('id', array_merge(
                    $productIds,
                    $recommendedProducts->pluck('id')->toArray()
                ))
                    ->whereNotIn('category', $originalCategories)
                    ->inRandomOrder()
                    ->limit($missing)
                    ->get();

                $recommendedProducts = $recommendedProducts->merge($additionalProducts);
            }

            // Végleges ajánlási lista (max. 4 termék)
            $recommendedProducts = $recommendedProducts->take(4);
        }

        return view('cart.mycart', compact('cartItems', 'recommendedProducts', 'totalPrice'));
    }

    /**
     * Termék hozzáadása a kosárhoz
     *
     * @param Request $request A HTTP kérés objektuma
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addToCart(Request $request)
    {
        $userId = auth()->id();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Be kell jelentkezned a vásárláshoz!');
        }

        // Bemeneti adatok validálása
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:50',
            'size' => 'required|string',
        ]);

        $product = Product::find($request->product_id);

        // Ha nem található a termék
        if (!$product) {
            return redirect()->route('cart.mycart')->with('error', 'A termék nem található!');
        }

        // Ellenőrizzük, hogy már létezik-e a termék a kosárban
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            // Ha igen, frissítjük a mennyiséget
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Ha nem, új rekordot hozunk létre
            Cart::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        // A választott méretet csak a session-ben tároljuk (nem kerül adatbázisba)
        session(['size_' . $request->product_id => $request->size]);

        // Esemény meghívása ajánlórendszerhez
        ProductAddedToCart::dispatch(auth()->id());

        return redirect()->route('cart.mycart')->with('success', 'A termék sikeresen hozzáadva a kosárhoz!');
    }

    /**
     * Kosárban lévő termék mennyiségének frissítése
     *
     * @param Request $request A HTTP kérés objektuma
     * @param Cart $cart A frissítendő kosárelem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCart(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Jogosultság ellenőrzése
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.mycart')->with('success', 'Cart updated!');
    }

    /**
     * Termék eltávolítása a kosárból
     *
     * @param Cart $cart A törlendő kosárelem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeFromCart(Cart $cart)
    {
        // Jogosultság ellenőrzése
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->delete();

        // A felhasználói profil újraszámítása az ajánlórendszerhez
        app(UserInterestProfileUpdater::class)->update(Auth::id());

        return redirect()->route('cart.mycart')->with('success', 'Item removed from cart!');
    }

    /**
     * 1 napnál régebbi kosárelemek törlése az adott felhasználótól
     *
     * @return void
     */
    public function cleanupUserCart()
    {
        $userId = auth()->id();

        if ($userId) {
            Cart::where('user_id', $userId)
                ->where('created_at', '<', now()->subDay())
                ->delete();
        }
    }
}
