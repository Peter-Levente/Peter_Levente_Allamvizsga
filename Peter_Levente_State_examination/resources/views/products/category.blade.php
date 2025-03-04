<!-- resources/views/category.blade.php -->
@extends('index')

@section('content')
    <div class="wrapper">
        <header>
            <nav class="header">
                <h1>Football Shop</h1>

                <!-- Ha a felhasználó nincs bejelentkezve -->
                @if (!auth()->check())
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Registration</a>
                @else
                    <a href="{{ route('logout') }}">Logout</a>
                    <span>Welcome, {{ auth()->user()->username }}!</span>
                @endif

                <div class="menu">
                    <a href="{{ route('products.category', ['category' => 'Clothings']) }}">Club Apparel</a>
                    <a href="{{ route('products.category', ['category' => 'Jerseys']) }}">Club Jerseys</a>
                    <a href="{{ route('products.category', ['category' => 'Shoes']) }}">Football Shoes</a>
                    <a href="{{ route('products.category', ['category' => 'Balls']) }}">Football Balls</a>
                </div>
            </nav>
        </header>

        <main>
            @foreach ($products as $product)
                <a href="{{ route('products.show', $product->id) }}" class="product">

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
