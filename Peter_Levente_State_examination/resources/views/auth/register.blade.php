<!DOCTYPE html>
<html lang="hu">
<head>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}"> <!-- Regisztrációs oldal CSS stíluslapja -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title> <!-- Az oldal címe -->
</head>
<body>
<h1>Regisztráció</h1> <!-- Oldal címsora -->

<!-- Regisztrációs űrlap -->
<form method="POST" action="{{ route('register') }}">
    @csrf
    <label>Email: <input type="email" name="email" value="{{ old('email') }}" required></label><br>
    @error('email') <span class="error">{{ $message }}</span> @enderror <!-- Hibajelzés -->

    <label>Teljes név: <input type="text" name="name" value="{{ old('name') }}" required></label><br>
    @error('name') <span class="error">{{ $message }}</span> @enderror

    <label>Jelszó: <input type="password" name="password" required></label><br>
    @error('password') <span class="error">{{ $message }}</span> @enderror

    <label>Jelszó megerősítés: <input type="password" name="password_confirmation" required></label><br>

    <button type="submit">Regisztráció</button>
</form>
</body>
</html>
