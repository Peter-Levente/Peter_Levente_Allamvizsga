<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Oldal címe -->
    <title>Football Shop - Category</title>

    <!-- FontAwesome ikonok és Google betűtípus -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">

    <!-- Vite assetek: CSS és JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="index-page category-page"> <!-- Extra class a kategóriaoldalhoz -->
<div class="wrapper">

    <!-- FEJLÉC -->
    <header>
        <nav class="header">
            <div class="topbar">
                <!-- Logó középen -->
                <div class="center">
                    <h1>Football Shop</h1>
                </div>

                <!-- Mobil ikon-sáv -->
                <div class="icon-bar">
                    <!-- Mobilmenü gomb -->
                    <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">☰</button>

                    <!-- Főoldal ikon -->
                    <a href="{{ url('/') }}"><i class="fa-solid fa-house"></i></a>

                    <!-- Be/Kijelentkezés ikonok -->
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

                    <!-- Rendelések és kosár ikon -->
                    <a href="{{ route('orders.myorders') }}"><i class="fa-solid fa-box"></i></a>
                    <a href="{{ route('cart.mycart') }}"><i class="fa-solid fa-cart-shopping"></i></a>

                    <!-- Mobil kereső ikon -->
                    <button type="button" class="search-toggle" onclick="toggleMobileSearchBar()">
                        <i class="fa fa-search"></i>
                    </button>

                    <!-- Mobil keresősáv -->
                    <form method="GET" action="{{ route('home') }}" id="mobile-search-bar" class="search-form">
                        <input type="text" name="search" placeholder="Search products" value="{{ request('search') }}">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>

                <!-- BAL OLDAL: autentikáció gombok -->
                <div class="left">
                    <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">☰</button>
                    <div class="auth-buttons">
                        @if (!auth()->check())
                            <a href="{{ route('login') }}">
                                <i class="fa-solid fa-right-to-bracket"></i><span>Login</span>
                            </a>
                            <a href="{{ route('register') }}">
                                <i class="fa-solid fa-user-plus"></i><span>Registration</span>
                            </a>
                        @else
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa-solid fa-right-from-bracket"></i><span>Logout</span>
                            </a>
                            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                                @csrf
                            </form>
                            <span class="welcome-message">Welcome, {{ auth()->user()->name }}!</span>
                        @endif
                    </div>
                </div>

                <!-- JOBB OLDAL: keresés, főoldal link, kosár és rendelések -->
                <div class="right">
                    <div class="header-actions">
                        <!-- Keresőmező -->
                        <div class="search-container">
                            <button type="button" class="search-toggle" onclick="toggleSearchBar()">
                                <i class="fa fa-search"></i>
                            </button>
                            <form method="GET" action="{{ route('home') }}" id="search-bar" class="search-form">
                                <input type="text" name="search" placeholder="Search products" value="{{ request('search') }}">
                                <button type="submit"><i class="fa fa-search"></i></button>
                            </form>
                        </div>

                        <!-- Főoldal ikon -->
                        <a href="{{ url('/') }}"><i class="fa-solid fa-house"></i> <span>Home</span></a>

                        <!-- Kosár és rendeléseim csak bejelentkezett felhasználóknak -->
                        @if (auth()->check())
                            <a href="{{ route('cart.mycart') }}"><i class="fa-solid fa-basket-shopping"></i> <span>My Cart</span></a>
                            <a href="{{ route('orders.myorders') }}"><i class="fa-solid fa-box"></i> <span>My Orders</span></a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kategória menü (asztali nézet) -->
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

    <!-- RENDEZÉSI ŰRLAP -->
    <div>
        <form method="GET" action="{{ url()->current() }}" id="sort-form" style="margin: 20px 0;">
            <label for="sort">Sort by:</label>
            <select name="sort" id="sort" onchange="handleSortAndScroll()">
                <option value="">-- Choose --</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z-A</option>
            </select>
            <button type="button" onclick="resetSorting()" style="margin-left: 10px;">Reset sorting</button>
        </form>
    </div>

    <!-- TERMÉKLISTA -->
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

    <!-- LÁBLÉC -->
    <footer>
        <div class="footer">
            <p>All rights reserved ©Football Shop 2025</p>
        </div>
    </footer>

    <!-- CHATBOT KOMPONENS -->
    @include('components.chatbot-widget')

</div>
</body>
</html>
