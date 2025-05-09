<?php

namespace App\Http\Controllers;

use App\Events\ProductAddedToCart;
use App\Services\UserInterestProfileUpdater;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // Kosár megtekintése
    public function index()
    {
        $this->cleanupUserCart();

        $userId = auth()->id();
        $cartItems = Cart::with('product')->where('user_id', $userId)->get();
        $productIds = $cartItems->pluck('product_id')->toArray();
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $recommendedProducts = collect();

        if ($cartItems->isNotEmpty()) {
            $originalCategories = $cartItems->pluck('product.category')->unique()->toArray();

            // Random termékek lekérése, kizárva a kosárban lévő kategóriákat és termékeket
            $candidates = Product::whereNotIn('id', $productIds)
                ->whereNotIn('category', $originalCategories)
                ->inRandomOrder()
                ->limit(100)
                ->get();

            // Csoportosítjuk kategóriák szerint
            $groupedByCategory = $candidates->groupBy('category');

            foreach ($groupedByCategory as $productsInCategory) {
                if ($productsInCategory->isNotEmpty()) {
                    $recommendedProducts->push($productsInCategory->random());
                }
            }

            // 🔥 Ha még nincs 4 ajánlás, egészítsük ki random termékekkel
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

            // Max 4 biztosan
            $recommendedProducts = $recommendedProducts->take(4);
        }

        return view('cart.mycart', compact('cartItems', 'recommendedProducts', 'totalPrice'));
    }





    // Termék hozzáadása a kosárhoz
    public function addToCart(Request $request)
    {
        $userId = auth()->id();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Be kell jelentkezned a vásárláshoz!');
        }

        // Validáció
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:50',
            'size' => 'required|string',
        ]);

        $product = Product::find($request->product_id);

        // Ha valamiért még mindig nem létezne a termék (bár az exists miatt ez ritka)
        if (!$product) {
            return redirect()->route('cart.mycart')->with('error', 'A termék nem található!');
        }

        // Kosár adatainak ellenőrzése az adatbázisban
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            // Ha már létezik a termék a kosárban, akkor frissítjük a mennyiséget
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Ha még nem létezik a termék a kosárban, hozzáadjuk
            Cart::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        // A méretet csak a session-ben tároljuk
        session(['size_' . $request->product_id => $request->size]);

        ProductAddedToCart::dispatch(auth()->id());

        return redirect()->route('cart.mycart')->with('success', 'A termék sikeresen hozzáadva a kosárhoz!');
    }


    // Mennyiség frissítése
    public function updateCart(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.mycart')->with('success', 'Cart updated!');
    }


    // Termék eltávolítása
    public function removeFromCart(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->delete();

        // 🔁 Profil újraszámolása, immár a törölt termék nélkül
        app(UserInterestProfileUpdater::class)->update(Auth::id());

        return redirect()->route('cart.mycart')->with('success', 'Item removed from cart!');
    }


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
