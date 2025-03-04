@extends('layouts.app')

@section('content')
    <h2>Regisztráció</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <input type="text" name="username" placeholder="Felhasználónév" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Jelszó" required>
        <input type="password" name="password_confirmation" placeholder="Jelszó megerősítése" required>
        <button type="submit">Regisztráció</button>
    </form>
@endsection
