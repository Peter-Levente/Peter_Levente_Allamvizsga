<!-- resources/views/cart.checkout.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>

    <!-- Laravel Vite assetek: stílus és JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="checkout-page">
<div class="container">
    <!-- Oldal címsor -->
    <h1>Checkout</h1>

    <!-- Hibaüzenet megjelenítése, ha van -->
    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <!-- Megrendelési űrlap -->
    <form method="POST" action="{{ route('order.place') }}">
        @csrf <!-- CSRF token Laravelhez -->

        <!-- Teljes név mező -->
        <label for="name">Full Name:</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" required>

        <!-- Szállítási cím -->
        <label for="address">Address:</label>
        <textarea name="address" id="address" rows="4" required>{{ old('address') }}</textarea>

        <!-- Telefonszám mező -->
        <label for="phone">Phone Number:</label>
        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required>

        <!-- Fizetési mód választómező -->
        <label for="payment_method">Payment Method:</label>
        <select name="payment_method" id="payment_method" required>
            <option value="Cash on Delivery">Cash on Delivery</option>
            <option value="Credit/Debit Card">Credit/Debit Card</option>
        </select>

        <!-- Összeg elrejtve (POST kéréssel megy az adatbázisba) -->
        <input type="hidden" name="total_amount" value="{{ $totalPrice }}">

        <!-- Megjelenített végösszeg -->
        <p>Total Price: {{ number_format($totalPrice, 2) }} lei</p>

        <!-- Megrendelés gomb -->
        <button type="submit">Place Order</button>
    </form>
</div>
</body>
</html>
