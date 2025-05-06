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

class OrderController extends Controller
{
    public function checkout()
    {
        $user = Auth::user();

        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        $totalPrice = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        return view('cart.checkout', compact('totalPrice'));  // View-hoz továbbítjuk
    }

    // Külön függvény a rendelés lekérdezésére
    private function getUserOrders($userId)
    {
        return Order::where('user_id', $userId)->with('items.product')->get();
    }

    // Rendelések megjelenítése
    public function myOrders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with('orderItems.product') // Betöltjük az items kapcsolatot és a termékeket is
            ->get();


        return view('orders.myorders', compact('orders'));  // A rendeléseket átadjuk a nézetnek
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'total_amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'phone' => 'required|string',
        ]);

        $userId = Auth::id();

        $order = DB::transaction(function () use ($request, $userId) {
            // Rendelés létrehozása
            $order = Order::create([
                'user_id' => $userId,
                'address' => $request->address,
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method,
                'phone' => $request->phone,
                'status' => 'pending'
            ]);

            // Kosár elemeinek lekérése
            $cartItems = Cart::where('user_id', $userId)->get();

            // Rendelési tételek mentése
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            // **Kosár kiürítése a rendelés után**
            Cart::where('user_id', $userId)->delete();

            return $order;
        });

        // A rendelés leadása után eseményt indítunk, amely jelezheti a rendszer más részeinek,
        // hogy új rendelés történt. Például: ajánlói profil frissítése.
        OrderPlaced::dispatch(auth()->id());

        return redirect()->route('orders.thank_you', ['order' => $order->id])
            ->with('success', 'A termék sikeresen le lett adva!');
    }


    public function destroy($id)
    {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $order->delete();

        // 👉 Itt is érdemes lehet újraszámolni a felhasználói érdeklődési profilt,
        // mert a rendelés megszűnik, így ne legyen hatással a további ajánlásokra
        // Visszairányítás a rendeléseket tartalmazó oldalra
        app(UserInterestProfileUpdater::class)->update(Auth::id());


        return redirect()->route('orders.myorders')->with('success', 'A rendelés sikeresen törölve lett.');
    }

    public function thankYou($orderId)
    {
        $order = Order::with('orderItems.product')->findOrFail($orderId);

        $purchasedProductIds = $order->orderItems->pluck('product.id')->toArray();
        $purchasedCategories = $order->orderItems->pluck('product.category')->unique()->toArray();

        $recommendedProducts = collect();

        if (!empty($purchasedProductIds)) {
            // Random termékek lekérése, kizárva a megvett kategóriákat és termékeket
            $candidates = Product::whereNotIn('id', $purchasedProductIds)
                ->whereNotIn('category', $purchasedCategories)
                ->inRandomOrder()
                ->limit(100)
                ->get();

            // Csoportosítjuk kategóriánként
            $groupedByCategory = $candidates->groupBy('category');

            foreach ($groupedByCategory as $productsInCategory) {
                if ($productsInCategory->isNotEmpty()) {
                    $recommendedProducts->push($productsInCategory->random());
                }
            }

            // Ha nincs meg 4 ajánlás, pótoljuk random más termékekkel
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

            $recommendedProducts = $recommendedProducts->take(4);
        }

        return view('orders.thank_you', compact('order', 'recommendedProducts'));
    }

}
