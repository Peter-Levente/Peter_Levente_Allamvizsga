<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    // Kosár megtekintése
    public function index()
    {
        $this->cleanupOldCarts(); // Régi kosártételek törlése

        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        $totalPrice = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        return view('cart.mycart', compact('cartItems', 'totalPrice'));
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

        return redirect()->route('cart.mycart')->with('success', 'Item removed from cart!');
    }


    public function cleanupOldCarts()
    {
        // Törli azokat a kosártételeket, amelyek 1 napnál régebbiek
        $deletedRows = Cart::where('created_at', '<', Carbon::now()->subDay())->delete();
        return $deletedRows;
    }

}
