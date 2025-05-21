<!-- resources/views/index.blade.php -->

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Shop - Home</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="index-page">
<div class="wrapper">
    <header>
        <nav class="header">
            <!-- FELSŐ SÁV: logó középen, bal/jobb oldalra a többi -->
            <div class="topbar">
                <!-- KÖZÉP: logó és mobilmenü gomb -->
                <div class="center">
                    <h1>Football Shop</h1>
                </div>

                <div class="icon-bar">
                    <!-- Menü ikon -->
                    <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">☰</button>

                    <!-- Auth ikonok -->
                    @if (!auth()->check())
                        <a href="{{ route('login') }}">
                            <i class="fa-solid fa-right-to-bracket"></i>
                        </a>
                        <a href="{{ route('register') }}">
                            <i class="fa-solid fa-user-plus"></i>
                        </a>
                    @else
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </a>
                        <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                            @csrf
                        </form>
                    @endif


                    <button type="button" class="search-toggle" onclick="toggleMobileSearchBar()" aria-label="Keresés ikon">
                        <i class="fa fa-search"></i>
                    </button>

                    <form method="GET" action="{{ route('home') }}" id="mobile-search-bar" class="search-form">
                        <input type="text" name="search" placeholder="Search products" value="{{ request('search') }}">
                        <button type="submit" aria-label="Keresés">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>


                    <!-- Kosár, rendelések -->
                    @if (auth()->check())
                        <a id="cart-icon" href="{{ route('cart.mycart') }}">
                            <i class="fa-solid fa-basket-shopping"></i>
                        </a>
                        <a id="orders-icon" href="{{ route('orders.myorders') }}">
                            <i class="fa-solid fa-box"></i>
                        </a>
                    @endif
                </div>

                <!-- BAL OLDAL: auth-gombok -->
                <div class="left">
                    <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                        ☰
                    </button>


                    <div class="auth-buttons">
                        @if (!auth()->check())
                            <a href="{{ route('login') }}">
                                <i class="fa-solid fa-right-to-bracket"></i> <span>Login</span>
                            </a>
                            <a href="{{ route('register') }}">
                                <i class="fa-solid fa-user-plus"></i> <span>Registration</span>
                            </a>
                        @else
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                <span>Logout</span>
                            </a>

                            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                                @csrf
                            </form>

                            <span class="welcome-message">Welcome, {{ auth()->user()->name }}!</span>
                        @endif
                    </div>
                </div>

                <!-- JOBB OLDAL: kereső + cart + orders -->
                <div class="right">
                    <div class="header-actions">
                        <!-- 🔍 Közös keresőmező az oldal tetején -->
                        <div class="search-container">
                            <button type="button" class="search-toggle" onclick="toggleSearchBar()" aria-label="Keresés ikon">
                                <i class="fa fa-search"></i>
                            </button>

                            <form method="GET" action="{{ route('home') }}" id="search-bar" class="search-form">
                                <input type="text" name="search" placeholder="Search products" value="{{ request('search') }}">
                                <button type="submit" aria-label="Keresés">
                                    <i class="fa fa-search"></i>
                                </button>
                            </form>
                        </div>

                        @if (!Request::is('/'))
                            <a href="{{ url('/') }}">
                                <i class="fa-solid fa-house"></i> <span>Home</span>
                            </a>
                        @endif

                        @if (auth()->check())
                            <a id="cart-icon" href="{{ route('cart.mycart') }}">
                                <i class="fa-solid fa-basket-shopping"></i> <span>My Cart</span>
                            </a>
                            <a id="orders-icon" href="{{ route('orders.myorders') }}">
                                <i class="fa-solid fa-box"></i> <span>My Orders</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>


            <!-- KATEGÓRIA MENÜ -->
            <div class="menu">
                <a href="{{ route('products.category', ['category' => 'Clothings']) }}">Club Apparel</a>
                <a href="{{ route('products.category', ['category' => 'Jerseys']) }}">Club Jerseys</a>
                <a href="{{ route('products.category', ['category' => 'Shoes']) }}">Football Shoes</a>
                <a href="{{ route('products.category', ['category' => 'Balls']) }}">Football Balls</a>
                <a href="{{ route('products.category', ['category' => 'Equipment']) }}">Football Equipment</a>
            </div>


            <!-- 📱 MOBILMENÜ különtéve (nem bal oldalon!) -->
            <div class="mobile-menu">
                <a href="{{ route('products.category', ['category' => 'Clothings']) }}">Club Apparel</a>
                <a href="{{ route('products.category', ['category' => 'Jerseys']) }}">Club Jerseys</a>
                <a href="{{ route('products.category', ['category' => 'Shoes']) }}">Football Shoes</a>
                <a href="{{ route('products.category', ['category' => 'Balls']) }}">Football Balls</a>
                <a href="{{ route('products.category', ['category' => 'Equipment']) }}">Football Equipment</a>
            </div>


        </nav>
    </header>
    <div>
        @if ($recommendedProducts->count() > 0)
            <section class="personal-recommendations">
                <h2>🎯 Neked ajánlott termékek</h2>
                <div class="recommendation-wrapper">
                    <button class="scroll-left" onclick="scrollRecommendations(-1)">&#10094;</button>

                    <div class="product-grid" id="recommendation-track">
                        @foreach ($recommendedProducts as $product)
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

        <form method="GET" action="{{ route('home') }}" id="sort-form" style="margin: 20px 0;">
            @if(request()->filled('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            <label for="sort">Sort by:</label>
            <select name="sort" id="sort" onchange="handleSortAndScroll()">

                <option value="">-- Choose --</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High
                </option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low
                </option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z-A</option>
            </select>

            <button type="button" onclick="resetSorting()" style="margin-left: 10px;">
                Reset sorting
            </button>
        </form>
    </div>

    <main id="product-list">
        @forelse ($products as $product)
            <a href="{{ route('products.details', $product->id) }}" class="product">
                <div>
                    <img src="{{ $product->image }}" alt="{{ $product->name }}">
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
            <p>All rights reserved ©Football Shop 2025</p>
        </div>
    </footer>

    @include('components.chatbot-widget')
</div>


</body>
</html>
