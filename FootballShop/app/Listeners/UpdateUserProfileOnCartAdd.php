<?php

namespace App\Listeners;

use App\Events\ProductAddedToCart;
use App\Services\UserInterestProfileUpdater;

// Ez a listener osztály figyeli a ProductAddedToCart eseményt,
// és frissíti a felhasználó érdeklődési profilját, amikor új terméket ad a kosarához
class UpdateUserProfileOnCartAdd
{
    /**
     * Ez a metódus automatikusan lefut, amikor a ProductAddedToCart esemény meghívódik.
     *
     * @param ProductAddedToCart $event Az esemény objektuma, amely tartalmazza a user ID-t
     * @return void
     */
    public function handle(ProductAddedToCart $event): void
    {
        // Meghívjuk a UserInterestProfileUpdater szolgáltatást,
        // amely újraszámolja a felhasználó embedding profilját
        // A kosárba tett termékek súlya 3×-os, így nagyobb hatással vannak az ajánlásokra
        app(UserInterestProfileUpdater::class)->update($event->userId);
    }
}
