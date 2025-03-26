<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(Request $request)
    {
        //mentsd el az előző oldalt
        if (!$request->session()->has('url.intended')) {
            $request->session()->put('url.intended', url()->previous());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        // Ellenőrizzük, hogy a "remember" checkbox be van-e jelölve
        $remember = $request->has('remember');

        if (Auth::attempt($validated, $remember)) {
            $request->session()->regenerate();

            // Ha az előző oldal a regisztrációs oldal volt, akkor /home-ra irányít
            if ($request->session()->previousUrl() && str_contains($request->session()->previousUrl(), route('register'))) {
                return redirect('/home');
            }

            // Ha nem a regisztrációról jött, akkor az eredeti oldalra megy
            return redirect()->intended();

        }

        throw ValidationException::withMessages([
            'email' => "Incorrect email or password!",
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Maradjon ugyanazon az oldalon
        return redirect()->intended(url()->previous());
    }
}
