@extends('layouts.app')

@section('content')
    <div class="container">
        <header>
            <nav class="navbar">
                <div>
                    <h1>My Cart</h1>
                </div>
                <div>
                    <a href="{{ route('home') }}">Home</a>
                </div>
            </nav>
        </header>

        <main class="cart-page">
            <h2 class="cart-title">Your Shopping Cart</h2>

            @if ($cartItems->isNotEmpty())
                <table class="cart-table">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($cartItems as $item)
                        <tr>
                            <td><img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}"></td>
                            <td class="item-name">{{ $item->product->name }}</td>
                            <td class="item-price">{{ number_format($item->product->price, 2) }} lei</td>
                            <td class="item-size">{{ $item->size }}</td>

                            <td class="item-quantity">
                                <form method="POST" action="{{ route('cart.update', $item->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" onchange="this.form.submit()">
                                </form>
                            </td>
                            <td class="item-total">{{ number_format($item->product->price * $item->quantity, 2) }} lei</td>
                            <td>
                                <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="cart-total">
                    Total: {{ number_format($totalPrice, 2) }} lei
                </div>

                <div class="cart-buttons">
                    <a href="{{ route('checkout') }}" class="btn btn-primary">Checkout</a>
                    <a href="{{ route('home') }}" class="btn btn-secondary">Continue Shopping</a>
                </div>
            @else
                <p class="empty-cart">Your cart is empty!</p>
            @endif
        </main>
    </div>
@endsection
