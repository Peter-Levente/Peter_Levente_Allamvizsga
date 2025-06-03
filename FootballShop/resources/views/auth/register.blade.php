<!-- resources/views/auth/register.blade.php -->

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Regisztráció</title>

    <!-- Laravel Vite által buildelt CSS és JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="register-page">

<!-- Fő cím -->
<h1>Regisztráció</h1>

<!-- Laravel POST űrlap a regisztrációhoz -->
<form method="POST" action="{{ route('register') }}">
    @csrf <!-- Laravel CSRF token védelme -->

    <!-- Email mező -->
    <label>Email:
        <input type="email" name="email" value="{{ old('email') }}" required>
    </label>
    @error('email') <!-- Validációs hiba megjelenítése -->
    <span class="error">{{ $message }}</span>
    @enderror

    <!-- Teljes név mező -->
    <label>Teljes név:
        <input type="text" name="name" value="{{ old('name') }}" required>
    </label>
    @error('name') <!-- Validációs hiba -->
    <span class="error">{{ $message }}</span>
    @enderror

    <!-- Jelszó mező -->
    <label>Jelszó:
        <input type="password" name="password" required>
    </label>
    @error('password') <!-- Jelszó hiba -->
    <span class="error">{{ $message }}</span>
    @enderror

    <!-- Jelszó megerősítés mező -->
    <label>Jelszó megerősítés:
        <input type="password" name="password_confirmation" required>
    </label>

    <!-- Beküldő gomb -->
    <button type="submit">Regisztráció</button>
</form>

</body>
</html>
