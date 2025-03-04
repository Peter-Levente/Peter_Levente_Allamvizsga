@extends('layouts.app')

@section('content')
    <h2>Bejelentkezés</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Jelszó" required>
        <label>
            <input type="checkbox" name="remember"> Emlékezz rám
        </label>
        <button type="submit">Bejelentkezés</button>
    </form>
@endsection
