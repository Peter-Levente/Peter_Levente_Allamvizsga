<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thank You</title>
    <!-- Külső CSS fájl betöltése -->
    <link rel="stylesheet" href="{{ asset('css/thank_you.css') }}">
</head>
<body>
<div class="container">
    <h1>Thank You for Your Order!</h1>
    <!-- Az order_id megjelenítése -->
    <p>Your order ID is: {{ $order->id }}</p>
    <!-- Visszairányítás a főoldalra -->
    <a href="{{ route('home') }}">Go back to homepage</a>
</div>

@if ($recommendedProducts->isNotEmpty())
    <section class="recommended-thankyou">
        <h3>🎁 Ajánlott termékek a következő vásárlásodhoz</h3>
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


</body>
</html>
