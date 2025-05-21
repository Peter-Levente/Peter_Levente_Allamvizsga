<!-- resources/views/orders.thank_you.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thank You</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="thankyou-page">
<div class="container">
    <h1>Thank You for Your Order!</h1>
    <p>Your order ID is: {{ $order->id }}</p>
    <a href="{{ route('home') }}">Go back to homepage</a>
</div>

@if ($recommendedProducts->isNotEmpty())
    <section class="recommended-thankyou">
        <h3>🎁 Recommended products for your next purchase</h3>
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
