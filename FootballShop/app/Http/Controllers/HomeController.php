<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// A főoldal (dashboard) megjelenítéséért felelős kontroller
class HomeController extends Controller
{
    /**
     * Konstruktor
     *
     * Meghatározza, hogy csak hitelesített (bejelentkezett) felhasználók érhetik el ezt a kontrollert.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * A kezdőoldal vagy dashboard megjelenítése
     *
     * Ez a metódus betölti az 'index' nézetet, amit a felhasználó lát.
     *
     * @return \Illuminate\Contracts\Support\Renderable A betöltendő nézet
     */
    public function index()
    {
        return view('index');
    }
}
