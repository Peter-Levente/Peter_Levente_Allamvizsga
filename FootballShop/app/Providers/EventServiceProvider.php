<?php

namespace App\Providers;

use App\Events\OrderPlaced;
use App\Events\ProductAddedToCart;
use App\Events\ProductViewed;
use App\Listeners\UpdateUserProfileOnCartAdd;
use App\Listeners\UpdateUserProfileOnOrder;
use App\Listeners\UpdateUserProfileOnView;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

// Az eseményekhez tartozó listenerek regisztrációját végző szolgáltató
class EventServiceProvider extends ServiceProvider
{
    /**
     * Az alkalmazás által használt esemény-listener párosítások.
     *
     * Minden eseményhez hozzárendelünk egy vagy több listenert,
     * amelyek automatikusan lefutnak, ha az esemény dispatch-elésre kerül.
     */
    protected $listen = [
        // Ha egy terméket megtekintenek, frissítjük a felhasználó profilját
        ProductViewed::class => [
            UpdateUserProfileOnView::class,
        ],

        // Ha egy terméket kosárba tesznek, frissítjük a profilt 3× súllyal
        ProductAddedToCart::class => [
            UpdateUserProfileOnCartAdd::class,
        ],

        // Ha rendelés történik, frissítjük a profilt 5× súllyal
        OrderPlaced::class => [
            UpdateUserProfileOnOrder::class,
        ],
    ];
}
