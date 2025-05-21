<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="my-orders-page">
<div class="container">
    <header>
        <h1>My Orders</h1>
        <a href="{{ url('/') }}">Home</a>
    </header>
    <main>
        @if ($orders->isNotEmpty())
            @foreach ($orders as $order)
                <div class="order">
                    <h2>Order #{{ $order->id }} ({{ $order->created_at }})</h2>
                    <p><strong>Name:</strong> {{ $order->user->name }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email }}</p>
                    <p><strong>Address:</strong> {{ $order->address }}</p>
                    <p><strong>Phone:</strong> {{ $order->phone }}</p>
                    <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>
                    <p><strong>Total Amount:</strong> {{ number_format($order->total_amount, 2) }} lei</p>

                    <h3>Items:</h3>
                    <ul>
                        @foreach ($order->orderItems as $item)
                            <li>
                                <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" style="width: 50px; height: 50px;">
                                {{ $item->product->name }}
                                (Size: {{ session('size_' . $item->product->id) }})
                                - {{ $item->quantity }} pcs @ {{ number_format($item->product->price, 2) }} lei each
                            </li>
                        @endforeach
                    </ul>

                    <form method="post" action="{{ route('orders.destroy', $order->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Cancel Order</button>
                    </form>
                </div>
            @endforeach
        @else
            <p>You have no orders yet.</p>
        @endif
    </main>
</div>
</body>
</html>
