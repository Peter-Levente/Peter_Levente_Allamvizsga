<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

// Bejelentkezésért és kijelentkezésért felelős kontroller
class LoginController extends Controller
{
    /**
     * Konstruktor
     *
     * Meghatározza, hogy csak kijelentkezett felhasználók érhetik el az osztály metódusait,
     * kivéve a 'logout' metódust.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Bejelentkezési űrlap megjelenítése
     *
     * Az aktuális oldal elmentése `url.intended` kulcs alatt, hogy sikeres belépés után
     * vissza lehessen irányítani oda.
     *
     * @param Request $request A HTTP kérés objektuma
     * @return \Illuminate\View\View A bejelentkezési nézet megjelenítése
     */
    public function showLoginForm(Request $request)
    {
        // Előző oldal elmentése csak ha még nincs eltárolva
        if (!$request->session()->has('url.intended')) {
            $request->session()->put('url.intended', url()->previous());
        }

        return view('auth.login');
    }

    /**
     * Bejelentkezési logika
     *
     * Validálja a bemeneti adatokat, ellenőrzi a hitelesítést, és a megfelelő oldalra irányít.
     * A "remember me" funkció is támogatott.
     *
     * @param Request $request A HTTP kérés objektuma
     * @return \Illuminate\Http\RedirectResponse Átirányítás a bejelentkező vagy más oldalra
     *
     * @throws ValidationException Ha a hitelesítés nem sikerül
     */
    public function login(Request $request)
    {
        // Validációs szabályok
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        // Ellenőrizd, hogy a "Remember Me" be van-e jelölve
        $remember = $request->has('remember');

        // Próbálkozás a hitelesítéssel
        if (Auth::attempt($validated, $remember)) {
            $request->session()->regenerate();

            // Ha regisztrációs oldalról jött, akkor ne oda térjen vissza
            if ($request->session()->previousUrl() && str_contains($request->session()->previousUrl(), route('register'))) {
                return redirect('/home');
            }

            // Sikeres hitelesítés után visszairányítás az eredeti oldalra
            return redirect()->intended();
        }

        // Hibás belépési adatok esetén kivétel dobása
        throw ValidationException::withMessages([
            'email' => "Incorrect email or password!",
        ]);
    }

    /**
     * Kijelentkeztetés
     *
     * A felhasználót kijelentkezteti, érvényteleníti a munkamenetet, majd visszairányítja az előző oldalra.
     *
     * @param Request $request A HTTP kérés objektuma
     * @return \Illuminate\Http\RedirectResponse Átirányítás a bejelentkező vagy más oldalra
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Maradjon ugyanazon az oldalon kijelentkezés után
        return redirect()->intended(url()->previous());
    }
}
