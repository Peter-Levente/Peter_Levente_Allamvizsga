<!-- resources/views/auth/login.blade.php -->

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <!-- Laravel Vite assetek (CSS + JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="login-page">

<!-- Bejelentkezési címsor -->
<h2>Bejelentkezés</h2>

<!-- Laravel bejelentkezési űrlap -->
<form method="POST" action="{{ route('login') }}">
    @csrf <!-- Laravel CSRF token a védelemhez -->

    <!-- Email mező -->
    <label>Email:
        <input type="email" name="email" required value="{{ old('email') }}">
    </label>
    @error('email')
    <!-- Hibajelzés, ha az email hibás -->
    <div class="error">{{ $message }}</div>
    @enderror

    <!-- Jelszó mező -->
    <label>Jelszó:
        <input type="password" name="password" required>
    </label>
    @error('password')
    <!-- Hibajelzés, ha a jelszó hibás -->
    <div class="error">{{ $message }}</div>
    @enderror

    <!-- "Emlékezz rám" opció -->
    <label>Emlékezz rám
        <input type="checkbox" name="remember">
    </label>

    <!-- Bejelentkezés gomb -->
    <button type="submit">Bejelentkezés</button>
</form>

<!-- Link a regisztrációhoz, ha még nincs fiók -->
<p>Nincs még fiókod? <a href="{{ route('register') }}">Regisztrálj itt</a></p>

</body>
</html>
