<!-- resources/views/auth/register.blade.php -->

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="register-page">

<h1>Regisztráció</h1>

<form method="POST" action="{{ route('register') }}">
    @csrf
    <label>Email:
        <input type="email" name="email" value="{{ old('email') }}" required>
    </label>
    @error('email')
    <span class="error">{{ $message }}</span>
    @enderror

    <label>Teljes név:
        <input type="text" name="name" value="{{ old('name') }}" required>
    </label>
    @error('name')
    <span class="error">{{ $message }}</span>
    @enderror

    <label>Jelszó:
        <input type="password" name="password" required>
    </label>
    @error('password')
    <span class="error">{{ $message }}</span>
    @enderror

    <label>Jelszó megerősítés:
        <input type="password" name="password_confirmation" required>
    </label>

    <button type="submit">Regisztráció</button>
</form>

</body>
</html>
