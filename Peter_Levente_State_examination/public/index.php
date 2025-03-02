<!-- resources/views/index.blade.php -->

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Shop - Home</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/PROJECT.css') }}">
</head>
<body>
<div class="wrapper">
    <header>
        <nav class="header">
            <div class="title">
                <h1>Football Shop</h1>
                <div class="auth-buttons">
                    @if (!auth()->check()) <!-- Ha nincs bejelentkezve -->
                    <a href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
                    <a href="{{ route('register') }}"><i class="fa-solid fa-user-plus"></i> Registration</a>
                    @endif
                </div>
                <div class="auth-buttons">
                    @if (auth()->check()) <!-- Ha be van jelentkezve -->
                    <a href="{{ route('logout') }}" class="auth-right"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                    <span class="welcome-message">Welcome, {{ auth()->user()->username }}!</span>
                    @endif
                </div>
                <div class="header-actions">
                    @if (auth()->check()) <!-- Ha be van jelentkezve -->
                    <a id="cart-icon" href="{{ route('cart.index') }}" class="header-link">
                        <i class="fa-solid fa-basket-shopping"></i> My Cart
                    </a>
                    <a id="orders-icon" href="{{ route('orders.index') }}" class="header-link">
                        <i class="fa-solid fa-box"></i> My Orders
                    </a>
                    @endif
                </div>
            </div>
            <div class="menu">
                <a href="{{ route('products.tracksuits') }}">Club Apparel</a>
                <a href="{{ route('products.jerseys') }}">Club Jerseys</a>
                <a href="{{ route('products.shoes') }}">Football Shoes</a>
                <a href="{{ route('products.balls') }}">Football Balls</a>
            </div>
        </nav>
    </header>

    <main>
        @forelse ($products as $product)
        <a href="{{ route('products.show', $product->id) }}" class="product">
            <div>
                <img src="{{ asset('storage/images/'.$product->image) }}" alt="{{ $product->name }}">
                <p>{{ $product->name }}</p>
                <p class="price">{{ number_format($product->price, 2) }} lei</p>
            </div>
        </a>
        @empty
        <p>No products available at the moment.</p>
        @endforelse
    </main>

    <footer>
        <div class="footer">
            <p>All rights reserved ©Football Shop 2024</p>
        </div>
    </footer>
</div>
</body>
</html>
