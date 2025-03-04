<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Shop - Product</title>
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
                    @if (!Request::is('/'))
                        <a href="{{ url('/') }}" class="home-link">
                            <i class="fa-solid fa-house"></i> Home
                        </a>
                    @endif

                    @if (auth()->check())
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
                <a href="{{ route('products.category', ['category' => 'Clothings']) }}">Club Apparel</a>
                <a href="{{ route('products.category', ['category' => 'Jerseys']) }}">Club Jerseys</a>
                <a href="{{ route('products.category', ['category' => 'Shoes']) }}">Football Shoes</a>
                <a href="{{ route('products.category', ['category' => 'Balls']) }}">Football Balls</a>
            </div>
        </nav>
    </header>

    <main>
        @if (isset($product))
            <div class="product-details">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                <h2>{{ $product->name }}</h2>
                <p class="price">{{ number_format($product->price, 2) }} lei</p>

                <form action="{{ route('cart.add', ['product' => $product->id]) }}" method="post">
                    @csrf
                    <label for="size">Size:</label>
                    @if ($product->category == 'Shoes') <!-- Ha a termék kategóriája cipő -->
                    <select name="size" id="size" required>
                        <option value="38">38</option>
                        <option value="38.5">38.5</option>
                        <option value="39">39</option>
                        <option value="40">40</option>
                        <option value="40.5">40.5</option>
                        <option value="41">41</option>
                        <option value="42">42</option>
                        <option value="42.5">42.5</option>
                        <option value="43">43</option>
                        <option value="44">44</option>
                        <option value="44.5">44.5</option>
                        <option value="45">45</option>
                        <option value="45.5">45.5</option>
                        <option value="46">46</option>
                        <option value="47">47</option>
                        <option value="47.5">47.5</option>
                    </select>
                    @elseif ($product->category == 'Balls') <!-- Ha a termék kategóriája labda -->
                    <select name="size" id="size" required>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                    @else <!-- Alapértelmezett: egyéb termékek -->
                    <select name="size" id="size" required>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                        <option value="XXL">XXL</option>
                    </select>
                    @endif
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" min="1" max="50" value="1" required>
                    <button type="submit">Add to Cart</button>
                </form>
            </div>
        @else
            <p>Product not found.</p>
        @endif
    </main>

    <footer>
        <div class="footer">
            <p>All rights reserved ©Football Shop 2025</p>
        </div>
    </footer>
</div>
</body>
</html>
