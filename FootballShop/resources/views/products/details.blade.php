<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Shop - Product</title>

    <!-- FontAwesome ikonok és betűtípus -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">

    <!-- Laravel Vite assetek -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="index-page category-page product-page">
<div class="wrapper">

    <!-- FEJLÉC -->
    <header>
        <nav class="header">
            <div class="topbar">

                <!-- Logó -->
                <div class="center">
                    <h1>Football Shop</h1>
                </div>

                <!-- MOBIL ikon-sáv -->
                <div class="icon-bar">
                    <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">☰</button>

                    <!-- Főoldal ikon -->
                    <a href="{{ url('/') }}"><i class="fa-solid fa-house"></i></a>

                    <!-- Belépés / Regisztráció vagy Kilépés -->
                    @if (!auth()->check())
                        <a href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket"></i></a>
                        <a href="{{ route('register') }}"><i class="fa-solid fa-user-plus"></i></a>
                    @else
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endif

                    <!-- Kosár és rendelések -->
                    <a href="{{ route('orders.myorders') }}"><i class="fa-solid fa-box"></i></a>
                    <a href="{{ route('cart.mycart') }}"><i class="fa-solid fa-cart-shopping"></i></a>

                    <!-- Kereső ikon -->
                    <button type="button" class="search-toggle" onclick="toggleMobileSearchBar()">
                        <i class="fa fa-search"></i>
                    </button>

                    <!-- Kereső űrlap mobilon -->
                    <form method="GET" action="{{ route('home') }}" id="mobile-search-bar" class="search-form">
                        <input type="text" name="search" placeholder="Search products" value="{{ request('search') }}">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>

                <!-- BAL oldali autentikációs gombok -->
                <div class="left">
                    <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">☰</button>
                    <div class="auth-buttons">
                        @if (!auth()->check())
                            <a href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket"></i><span>Login</span></a>
                            <a href="{{ route('register') }}"><i class="fa-solid fa-user-plus"></i><span>Registration</span></a>
                        @else
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa-solid fa-right-from-bracket"></i><span>Logout</span>
                            </a>
                            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">@csrf</form>
                            <span class="welcome-message">Welcome, {{ auth()->user()->name }}!</span>
                        @endif
                    </div>
                </div>

                <!-- JOBB oldal: kereső, főoldal, kosár, rendelések -->
                <div class="right">
                    <div class="header-actions">
                        <div class="search-container">
                            <button type="button" class="search-toggle" onclick="toggleSearchBar()">
                                <i class="fa fa-search"></i>
                            </button>
                            <form method="GET" action="{{ route('home') }}" id="search-bar" class="search-form">
                                <input type="text" name="search" placeholder="Search products" value="{{ request('search') }}">
                                <button type="submit"><i class="fa fa-search"></i></button>
                            </form>
                        </div>

                        <a href="{{ url('/') }}"><i class="fa-solid fa-house"></i> <span>Home</span></a>

                        @if (auth()->check())
                            <a href="{{ route('cart.mycart') }}"><i class="fa-solid fa-basket-shopping"></i> <span>My Cart</span></a>
                            <a href="{{ route('orders.myorders') }}"><i class="fa-solid fa-box"></i><span>My Orders</span></a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kategória menü -->
            <div class="menu">
                <a href="{{ route('products.category', ['category' => 'Clothings']) }}">Club Apparel</a>
                <a href="{{ route('products.category', ['category' => 'Jerseys']) }}">Club Jerseys</a>
                <a href="{{ route('products.category', ['category' => 'Shoes']) }}">Football Shoes</a>
                <a href="{{ route('products.category', ['category' => 'Balls']) }}">Football Balls</a>
                <a href="{{ route('products.category', ['category' => 'Equipment']) }}">Football Equipment</a>
            </div>

            <!-- Mobilmenü -->
            <div class="mobile-menu">
                <a href="{{ route('products.category', ['category' => 'Clothings']) }}">Club Apparel</a>
                <a href="{{ route('products.category', ['category' => 'Jerseys']) }}">Club Jerseys</a>
                <a href="{{ route('products.category', ['category' => 'Shoes']) }}">Football Shoes</a>
                <a href="{{ route('products.category', ['category' => 'Balls']) }}">Football Balls</a>
                <a href="{{ route('products.category', ['category' => 'Equipment']) }}">Football Equipment</a>
            </div>
        </nav>
    </header>

    <!-- FŐTARTALOM -->
    <main>
        @if (isset($product))
            <div class="product-details">
                <!-- Termék képe -->
                <div class="product-image">
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                </div>

                <!-- Termék részletei -->
                <div class="product-info">
                    <h2>{{ $product->name }}</h2>
                    <p class="price">Price: {{ number_format($product->price, 2) }} lei</p>
                    <p class="description">{{ $product->description }}</p>

                    <!-- Kosárba helyezés űrlap -->
                    <form action="{{ route('cart.add', ['product' => $product->id]) }}" method="post">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <!-- Méretválasztó, ha nem Equipment kategória -->
                        @if ($product->category != 'Equipment')
                            <label for="size">Size:</label>
                            <select name="size" id="size" required>
                                <option value="" selected disabled>Select size</option>

                                <!-- Cipőméretek -->
                                @if ($product->category == 'Shoes')
                                    <option value="36">36</option>
                                    <option value="36.5">36.5</option>
                                    <option value="37">37</option>
                                    <option value="37.5">37.5</option>
                                    <option value="38">38</option>
                                    <option value="38.5">38.5</option>
                                    <option value="39">39</option>
                                    <option value="39.5">39.5</option>
                                    <option value="40">40</option>
                                    <option value="40.5">40.5</option>
                                    <option value="41">41</option>
                                    <option value="41.5">41.5</option>
                                    <option value="42">42</option>
                                    <option value="42.5">42.5</option>
                                    <option value="43">43</option>
                                    <option value="43.5">43.5</option>
                                    <option value="44">44</option>
                                    <option value="44.5">44.5</option>
                                    <option value="45">45</option>
                                    <option value="45.5">45.5</option>
                                    <option value="46">46</option>
                                    <option value="46.5">46.5</option>
                                    <option value="47">47</option>
                                    <option value="47.5">47.5</option>
                                    <option value="48">48</option>
                                    <option value="48.5">48.5</option>
                                @elseif ($product->category == 'Balls')
                                    <!-- Labdaméretek -->
                                    <option value="1">1 (Mini)</option>
                                    <option value="2">2</option>
                                    <option value="3">3 (Junior)</option>
                                    <option value="4">4 (Youth)</option>
                                    <option value="5">5 (Official Adult Size)</option>
                                @else
                                    <!-- Ruhaméretek -->
                                    <option value="XS">XS</option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                    <option value="XXL">XXL</option>
                                    <option value="3XL">3XL</option>
                                    <option value="4XL">4XL</option>
                                @endif
                            </select>
                        @endif

                        <!-- Mennyiség -->
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="quantity" id="quantity" min="1" max="50" value="1" required>

                        <!-- Kosárba gomb -->
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>
            </div>
        @else
            <p>Product not found.</p>
        @endif

        <!-- Hasonló termékek ajánlása -->
        @if (!empty($similarProducts))
            <section class="personal-recommendations">
                <h2>🎯 Similar products that might interest you</h2>
                <div class="recommendation-wrapper">
                    <button class="scroll-left" onclick="scrollRecommendations(-1)">&#10094;</button>

                    <div class="product-grid" id="recommendation-track">
                        @foreach ($similarProducts as $product)
                            <a href="{{ route('products.details', ['id' => $product->id]) }}" class="product-card">
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                                <h4>{{ $product->name }}</h4>
                                <p>{{ number_format($product->price, 2) }} lei</p>
                            </a>
                        @endforeach
                    </div>

                    <button class="scroll-right" onclick="scrollRecommendations(1)">&#10095;</button>
                </div>
            </section>
        @endif
    </main>

    <!-- LÁBLÉC -->
    <footer>
        <div class="footer">
            <p>All rights reserved ©Football Shop 2025</p>
        </div>
    </footer>

    <!-- Chatbot komponens -->
    @include('components.chatbot-widget')

</div>
</body>
</html>
