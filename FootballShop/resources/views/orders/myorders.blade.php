<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metaadatok és oldal címe -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>

    <!-- Laravel Vite assetek (CSS, JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="my-orders-page">
<div class="container">

    <!-- Fejléc -->
    <header>
        <h1>My Orders</h1>
        <a href="{{ url('/') }}">Home</a> <!-- Visszalépés a főoldalra -->
    </header>

    <!-- Fő tartalom -->
    <main>
        @if ($orders->isNotEmpty())
            <!-- Bejárjuk a felhasználó rendeléseit -->
            @foreach ($orders as $order)
                <div class="order">
                    <!-- Rendelés fejléc és alapadatok -->
                    <h2>Order #{{ $order->id }} ({{ $order->created_at }})</h2>
                    <p><strong>Name:</strong> {{ $order->user->name }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email }}</p>
                    <p><strong>Address:</strong> {{ $order->address }}</p>
                    <p><strong>Phone:</strong> {{ $order->phone }}</p>
                    <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>
                    <p><strong>Total Amount:</strong> {{ number_format($order->total_amount, 2) }} lei</p>

                    <!-- Tétellista -->
                    <h3>Items:</h3>
                    <ul>
                        @foreach ($order->orderItems as $item)
                            <li>
                                <!-- Termékkép -->
                                <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" style="width: 50px; height: 50px;">

                                <!-- Terméknév és méret -->
                                {{ $item->product->name }}
                                (Size: {{ session('size_' . $item->product->id) }})

                                <!-- Mennyiség és egységár -->
                                - {{ $item->quantity }} pcs @ {{ number_format($item->product->price, 2) }} lei each
                            </li>
                        @endforeach
                    </ul>

                    <!-- Rendelés törlése (DELETE HTTP metódus) -->
                    <form method="post" action="{{ route('orders.destroy', $order->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Cancel Order</button>
                    </form>
                </div>
            @endforeach
        @else
            <!-- Ha nincs rendelés -->
            <p>You have no orders yet.</p>
        @endif
    </main>
</div>
</body>
</html>
