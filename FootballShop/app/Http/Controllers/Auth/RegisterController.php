<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Regisztrációért felelős kontroller
class RegisterController extends Controller
{
    /**
     * Regisztrációs űrlap megjelenítése
     *
     * @return \Illuminate\View\View A regisztrációs nézet megjelenítése
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Regisztrációs folyamat kezelése
     *
     * A felhasználó által megadott adatok validálása és új felhasználó létrehozása az adatbázisban.
     *
     * @param Request $request A HTTP kérés objektuma
     * @return \Illuminate\Http\RedirectResponse Átirányítás a bejelentkezési oldalra sikeres regisztráció után
     */
    public function register(Request $request)
    {
        // Validációs szabályok
        $request->validate([
            'email' => 'required|email|unique:users',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Új felhasználó létrehozása
        User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password),
        ]);

        // Átirányítás a bejelentkezési oldalra
        return redirect('/login')->with('success', 'Sikeres regisztráció! Most jelentkezz be.');
    }
}
