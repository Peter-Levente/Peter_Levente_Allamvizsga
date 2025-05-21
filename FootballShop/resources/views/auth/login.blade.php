<!-- resources/views/auth/login.blade.php -->

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-page">

<h2>Bejelentkezés</h2>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <label>Email:
        <input type="email" name="email" required value="{{ old('email') }}">
    </label>
    @error('email')
    <div class="error">{{ $message }}</div>
    @enderror

    <label>Jelszó:
        <input type="password" name="password" required>
    </label>
    @error('password')
    <div class="error">{{ $message }}</div>
    @enderror

    <label>Emlékezz rám
        <input type="checkbox" name="remember">
    </label>

    <button type="submit">Bejelentkezés</button>
</form>

<p>Nincs még fiókod? <a href="{{ route('register') }}">Regisztrálj itt</a></p>

</body>
</html>
