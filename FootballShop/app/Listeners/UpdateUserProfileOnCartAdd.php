<?php

namespace App\Listeners;

use App\Events\ProductAddedToCart;

use App\Services\UserInterestProfileUpdater;

// Ez a listener figyeli a ProductAddedToCart eseményt
class UpdateUserProfileOnCartAdd
{
    // Ez a metódus akkor fut le, amikor a ProductAddedToCart esemény meghívódik
    public function handle(ProductAddedToCart $event): void
    {
        // Meghívja a UserInterestProfileUpdater osztály update() metódusát,
        // és átadja a felhasználó azonosítóját,
        // hogy frissítse a felhasználó érdeklődési embedding profilját
        // (a kosárba helyezett termékek 3× súllyal szerepelnek az átlagolásban)
        app(UserInterestProfileUpdater::class)->update($event->userId);
    }
}
