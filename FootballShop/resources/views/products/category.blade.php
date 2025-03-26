<!-- resources/views/category.blade.php -->
@extends('index')

@section('content')
    <div class="wrapper">
        <header>
            <nav class="header">
                <div class="title">
                    <h1>Football Shop</h1>

                    <div class="auth-buttons">
                        @if (!Request::is('/'))
                            <a href="{{ url('/') }}" class="home-link">
                                <i class="fa-solid fa-house"></i> Home
                            </a>
                        @endif

                        @if (!auth()->check())
                            <!-- Ha nincs bejelentkezve -->
                            <a href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
                            <a href="{{ route('register') }}"><i class="fa-solid fa-user-plus"></i> Registration</a>
                        @endif
                    </div>

                    <div class="auth-buttons">
                        <div class="home-container">
                            @if (!Request::is('/'))
                                <a href="{{ url('/') }}" class="home-link">
                                    <i class="fa-solid fa-house"></i> Home
                                </a>
                            @endif
                        </div>
                        @if (auth()->check())
                            <!-- Ha be van jelentkezve -->
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa-solid fa-right-from-bracket"></i> Logout
                            </a>

                            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                                @csrf
                            </form>

                            <span class="welcome-message">Welcome, {{ auth()->user()->name }}!</span>
                        @endif
                    </div>

                    <div class="header-actions">
                        @if (auth()->check())
                            <!-- Ha be van jelentkezve -->
                            <a id="cart-icon" href="{{ route('cart.mycart') }}" class="header-link">
                                <i class="fa-solid fa-basket-shopping"></i> My Cart
                            </a>
                            <a id="orders-icon" href="{{ route('orders.myorders') }}" class="header-link">
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
                    <a href="{{ route('products.category', ['category' => 'Equipment']) }}">Football Equipment</a>
                </div>
            </nav>
        </header>

        <main>
            @foreach ($products as $product)
                <a href="{{ route('products.details', $product->id) }}" class="product">

                    <div class="product">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}">
                        <p>{{ $product->name }}</p>
                        <p class="price">{{ number_format($product->price, 2) }} lei</p>
                    </div>
            @endforeach
        </main>

        <footer>
            <p>All rights reserved ©Football Shop 2024</p>
        </footer>
    </div>
@endsection
