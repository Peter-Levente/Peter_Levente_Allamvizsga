<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\UserInterestProfileUpdater;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// A rendeléseket és azok kezelését végző kontroller
class OrderController extends Controller
{
    /**
     * Rendelés véglegesítés oldal megjelenítése a végösszeggel
     *
     * @return \Illuminate\View\View
     */
    public function checkout()
    {
        $user = Auth::user();

        // A felhasználó kosarának betöltése
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        // Teljes ár kiszámítása
        $totalPrice = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        return view('cart.checkout', compact('totalPrice'));
    }

    /**
     * A felhasználó korábbi rendeléseinek megjelenítése
     *
     * @return \Illuminate\View\View
     */
    public function myOrders()
    {
        $user = Auth::user();

        // A rendeléseket időrendben lekérjük
        $orders = Order::where('user_id', $user->id)
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.myorders', compact('orders'));
    }

    /**
     * Rendelés létrehozása az aktuális kosár alapján
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validáció
        $request->validate([
            'address' => 'required|string',
            'total_amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'phone' => 'required|string',
        ]);

        $userId = Auth::id();

        // Adatbázis tranzakcióban végezzük a rendelés, rendelési tételek létrehozását és a kosár törlését
        $order = DB::transaction(function () use ($request, $userId) {
            // Rendelés mentése
            $order = Order::create([
                'user_id' => $userId,
                'address' => $request->address,
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method,
                'phone' => $request->phone,
                'status' => 'pending'
            ]);

            // Kosár lekérdezése
            $cartItems = Cart::where('user_id', $userId)->get();

            // Kosár elemekből rendelési tételek létrehozása
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            // Kosár ürítése
            Cart::where('user_id', $userId)->delete();

            return $order;
        });

        // Esemény küldése az ajánlórendszer vagy más komponens értesítésére
        OrderPlaced::dispatch(auth()->id());

        // Átirányítás a "köszönő" oldalra
        return redirect()->route('orders.thank_you', ['order' => $order->id])
            ->with('success', 'A termék sikeresen le lett adva!');
    }

    /**
     * Rendelés törlése (csak saját rendelés)
     *
     * @param int $id A törlendő rendelés azonosítója
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // A felhasználó csak saját rendelését törölheti
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $order->delete();

        // Az ajánlórendszer profil újraszámítása a törölt rendelés nélkül
        app(UserInterestProfileUpdater::class)->update(Auth::id());

        return redirect()->route('orders.myorders')->with('success', 'A rendelés sikeresen törölve lett.');
    }

    /**
     * "Köszönő oldal" megjelenítése a rendelés után, ajánlott termékekkel
     *
     * @param int $orderId A leadott rendelés azonosítója
     * @return \Illuminate\View\View
     */
    public function thankYou($orderId)
    {
        // A rendelés és a hozzá tartozó tételek lekérdezése
        $order = Order::with('orderItems.product')->findOrFail($orderId);

        // A megvásárolt termékek ID-i és kategóriái
        $purchasedProductIds = $order->orderItems->pluck('product.id')->toArray();
        $purchasedCategories = $order->orderItems->pluck('product.category')->unique()->toArray();

        $recommendedProducts = collect();

        if (!empty($purchasedProductIds)) {
            // Kizárt termékek és kategóriák alapján új ajánlott termékek lekérdezése
            $candidates = Product::whereNotIn('id', $purchasedProductIds)
                ->whereNotIn('category', $purchasedCategories)
                ->inRandomOrder()
                ->limit(100)
                ->get();

            // Kategóriánként egy véletlenszerű terméket ajánlunk
            $groupedByCategory = $candidates->groupBy('category');

            foreach ($groupedByCategory as $productsInCategory) {
                if ($productsInCategory->isNotEmpty()) {
                    $recommendedProducts->push($productsInCategory->random());
                }
            }

            // Ha nincs meg a 4 ajánlás, kiegészítjük véletlenszerű termékekkel
            if ($recommendedProducts->count() < 4) {
                $missing = 4 - $recommendedProducts->count();

                $additionalProducts = Product::whereNotIn('id', array_merge(
                    $purchasedProductIds,
                    $recommendedProducts->pluck('id')->toArray()
                ))
                    ->whereNotIn('category', $purchasedCategories)
                    ->inRandomOrder()
                    ->limit($missing)
                    ->get();

                $recommendedProducts = $recommendedProducts->merge($additionalProducts);
            }

            // Max 4 ajánlott termék
            $recommendedProducts = $recommendedProducts->take(4);
        }

        return view('orders.thank_you', compact('order', 'recommendedProducts'));
    }
}
