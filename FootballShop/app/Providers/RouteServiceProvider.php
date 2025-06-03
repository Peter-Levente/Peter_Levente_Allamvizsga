<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

// A route-ok (útvonalak) betöltéséért felelős szolgáltató osztály
class RouteServiceProvider extends ServiceProvider
{
    /**
     * Szolgáltatások regisztrálása.
     *
     * Itt lehetne bindingeket, singletonokat stb. definiálni,
     * de jelen esetben nincs szükség külön regisztrációra.
     */
    public function register(): void
    {
        // Nem használjuk ki ebben az alkalmazásban
    }

    /**
     * Szolgáltatások bootstrap-elése (pl. route-ok betöltése)
     */
    public function boot(): void
    {
        // Meghívjuk az alapértelmezett boot logikát
        parent::boot();

        // API útvonalak regisztrálása
        $this->mapApiRoutes();

        // Webes útvonalak regisztrálása
        $this->mapWebRoutes();
    }

    /**
     * API útvonalak betöltése a `routes/api.php` fájlból
     *
     * A route-ok `api` middleware csoport alá kerülnek, és URL-jük `api/` prefixet kap
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    /**
     * Webes útvonalak betöltése a `routes/web.php` fájlból
     *
     * Ezekhez az útvonalakhoz `web` middleware-ek tartoznak (session, CSRF, stb.)
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }
}
