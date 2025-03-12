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
</body>
</html>
