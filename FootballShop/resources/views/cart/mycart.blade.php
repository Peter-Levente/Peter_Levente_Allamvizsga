<!-- resources/views/cart.mycart.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>

    <!-- Laravel Vite által buildelt CSS/JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="cart-page">
<div class="container">

    <!-- Fejléc navigáció -->
    <header>
        <nav class="navbar">
            <div>
                <h1>My Cart</h1>
            </div>
            <div>
                <a href="{{ url('/') }}">Home</a> <!-- Link vissza a főoldalra -->
            </div>
        </nav>
    </header>

    <!-- Fő szekció: kosártartalom -->
    <main class="cart-section">
        <h2 class="cart-title">Your Shopping Cart</h2>

        <!-- Ha vannak termékek a kosárban -->
        @if ($cartItems->isNotEmpty())
            <!-- Kosártábla -->
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
                        <!-- Termékkép -->
                        <td data-label="Product">
                            <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}">
                        </td>

                        <!-- Név -->
                        <td data-label="Name" class="item-name">{{ $item->product->name }}</td>

                        <!-- Egységár -->
                        <td data-label="Price" class="item-price">{{ number_format($item->product->price, 2) }} lei</td>

                        <!-- Méret  -->
                        <td data-label="Size" class="item-size">{{ session('size_' . $item->product->id) }}</td>

                        <!-- Mennyiség (formon belül automatikus frissítéssel) -->
                        <td data-label="Quantity" class="item-quantity">
                            <form method="post" action="{{ route('cart.update', $item->id) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="cart_id" value="{{ $item->id }}">
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" onchange="this.form.submit()">
                            </form>
                        </td>

                        <!-- Összesített ár -->
                        <td data-label="Total" class="item-total">
                            {{ number_format($item->product->price * $item->quantity, 2) }} lei
                        </td>

                        <!-- Eltávolítás gomb -->
                        <td data-label="Actions">
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

            <!-- Teljes összeg -->
            <div class="cart-total">
                Total: {{ number_format($totalPrice, 2) }} lei
            </div>

            <!-- Gombok: fizetés vagy vissza a boltba -->
            <div class="cart-buttons">
                <button onclick="location.href='{{ route('cart.checkout') }}'">Checkout</button>
                <button onclick="location.href='{{ url('/') }}'">Continue Shopping</button>
            </div>
        @else
            <!-- Ha a kosár üres -->
            <p class="empty-cart">Your cart is empty!</p>
        @endif

        <!-- Keresztértékesítési ajánlások -->
        @if ($recommendedProducts->isNotEmpty())
            <section class="recommended-cross-sell">
                <h3>🛍️ You should also check these out</h3>
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
