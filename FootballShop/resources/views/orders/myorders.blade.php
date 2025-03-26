<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="{{ asset('css/my_orders.css') }}"> <!-- A rendelési oldal stíluslapja -->
</head>
<body>
<div class="container">
    <header>
        <h1>My Orders</h1> <!-- Rendelések oldal cím -->
        <a href="{{ url('/') }}">Home</a> <!-- Link a főoldalra -->
    </header>
    <main>
        @if ($orders->isNotEmpty()) <!-- Ha vannak rendelései -->
        @foreach ($orders as $order) <!-- Minden rendelést végigiterálunk -->
        <div class="order"> <!-- Rendelés adatainak megjelenítése -->
            <h2>Order #{{ $order->id }} ({{ $order->created_at }})</h2>
            <p><strong>Name:</strong> {{ $order->user->name }}</p>
            <p><strong>Email:</strong> {{ $order->user->email }}</p>
            <p><strong>Address:</strong> {{ $order->address }}</p>
            <p><strong>Phone:</strong> {{ $order->phone }}</p>
            <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>
            <p><strong>Total Amount:</strong> {{ number_format($order->total_amount, 2) }} lei</p> <!-- Rendelés összesített ára -->

            <h3>Items:</h3>
            <ul>
                @foreach ($order->items as $item) <!-- A rendelés tételeit végigiteráljuk -->
                <li>
                    <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" style="width: 50px; height: 50px;">
                    {{ $item->product->name }}
                    (Size: {{ session('size_' . $item->product->id) }})
                    - {{ $item->quantity }} pcs @ {{ number_format($item->product->price, 2) }} lei each
                </li>
                @endforeach
            </ul>

            <!-- Rendelés törlésére szolgáló űrlap -->
            <form method="post" action="{{ route('orders.destroy', $order->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit">Cancel Order</button> <!-- Törlés gomb -->
            </form>
        </div>
        @endforeach
        @else <!-- Ha nincsenek rendelései -->
        <p>You have no orders yet.</p> <!-- Üzenet, ha nincs rendelés -->
        @endif
    </main>
</div>
</body>
</html>
