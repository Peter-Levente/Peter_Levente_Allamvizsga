<head>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}"> <!-- Bejelentkezési stíluslap -->
</head>

<h2>Bejelentkezés</h2>

<!-- Bejelentkezési űrlap -->
<form method="POST" action="{{ route('login') }}">
    @csrf
    <label>Email: <input type="email" name="email" required value="{{ old('email') }}"></label><br> <!-- Email mező -->

    @error('email')
    <div class="error">{{ $message }}</div>
    @enderror

    <label>Jelszó: <input type="password" name="password" required></label><br> <!-- Jelszó mező -->

    @error('password')
    <div class="error">{{ $message }}</div>
    @enderror

    <label>Emlékezz rám <input type="checkbox" name="remember"></label><br> <!-- Emlékezz rám jelölőnégyzet -->

    <button type="submit">Bejelentkezés</button> <!-- Bejelentkezés gomb -->
</form>

<!-- Regisztrációs link -->
<p>Nincs még fiókod? <a href="{{ route('register') }}">Regisztrálj itt</a></p> <!-- Regisztrációs oldal linkje -->
