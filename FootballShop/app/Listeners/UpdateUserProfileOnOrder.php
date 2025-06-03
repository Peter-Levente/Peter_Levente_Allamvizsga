<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Services\UserInterestProfileUpdater;

// Ez a listener figyeli az OrderPlaced eseményt,
// és frissíti a felhasználó érdeklődési profilját a rendelés alapján
class UpdateUserProfileOnOrder
{
    /**
     * Ez a metódus automatikusan lefut, amikor az OrderPlaced esemény meghívódik.
     *
     * A cél: a vásárlási adatok alapján frissíteni a felhasználó
     * személyre szabott embedding profilját, amelyet az ajánlórendszer használ.
     *
     * @param OrderPlaced $event Az esemény, amely tartalmazza a felhasználó azonosítóját
     * @return void
     */
    public function handle(OrderPlaced $event): void
    {
        // Frissítjük a felhasználó érdeklődési profilját a rendelés alapján.
        // A megvásárolt termékek nagy súllyal járulnak hozzá a profilhoz (pl. 5× súly)
        app(UserInterestProfileUpdater::class)->update($event->userId);
    }
}
