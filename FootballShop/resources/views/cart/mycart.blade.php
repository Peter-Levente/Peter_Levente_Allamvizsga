<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
</head>
<body>
<div class="container">
    <header>
        <nav class="navbar">
            <div>
                <h1>My Cart</h1>
            </div>
            <div>
                <a href="{{ url('/') }}">Home</a>
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
                        <td class="item-size">{{ session('size_' . $item->product->id) }}</td>
                        <td class="item-quantity">
                            <form method="post" action="{{ route('cart.update', $item->id) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="cart_id" value="{{ $item->id }}">
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" onchange="this.form.submit()">
                            </form>
                        </td>

                        <td class="item-total">{{ number_format($item->product->price * $item->quantity, 2) }} lei</td>
                        <td>
                            <form method="post" action="{{ route('cart.remove', $item->id) }}">
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
                <button onclick="location.href='{{ route('cart.checkout') }}'">Checkout</button>
                <button onclick="location.href='{{ url('/') }}'">Continue Shopping</button>
            </div>
        @else
            <p class="empty-cart">Your cart is empty!</p>
        @endif

        @if (!empty($similarProducts))
            <section class="related-products">
                <h3>Hasonló termékek, amik érdekelhetnek</h3>
                <div class="product-grid">
                    @foreach ($similarProducts as $similar)
                        <a href="{{ route('products.details', ['id' => $similar->id]) }}" class="product-card">
                            <img src="{{ asset($similar->image) }}" alt="{{ $similar->name }}">
                            <h4>{{ $similar->name }}</h4>
                            <p>{{ number_format($similar->price, 2) }} lei</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif


        @if ($recommendedProducts->isNotEmpty())
            <section class="recommended-cross-sell">
                <h3>🛍️ Ezt még érdemes megnézned</h3>
                <div class="product-grid">
                    @foreach ($recommendedProducts as $product)
                        <a href="{{ route('products.details', ['id' => $product->id]) }}" class="product-card">
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                            <h4>{{ $product->name }}</h4>
                            <p>{{ number_format($product->price, 2) }} lei</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif


    </main>
</div>
</body>
</html>
