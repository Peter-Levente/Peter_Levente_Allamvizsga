<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\Product;
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
            ->with('items.product') // Betöltjük az items kapcsolatot és a termékeket is
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
                    'price' => $item->product->price, // Feltételezve, hogy a terméknek van ára
                ]);
            }

            // **Kosár kiürítése a rendelés után**
            Cart::where('user_id', $userId)->delete();

            return $order;
        });

        return redirect()->route('orders.thank_you', ['order' => $order->id])
            ->with('success', 'A termék sikeresen le lett adva!');
    }


    public function destroy($id)
    {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $order->delete();

        // Visszairányítás a rendeléseket tartalmazó oldalra
        return redirect()->route('orders.myorders')->with('success', 'A rendelés sikeresen törölve lett.');
    }


    public function thankYou(Order $order)
    {
        return view('orders.thank_you', compact('order'));
    }
}
