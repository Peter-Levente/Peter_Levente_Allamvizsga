<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Services\UserInterestProfileUpdater;

class UpdateUserProfileOnOrder
{
    /**
     * Ez a metódus akkor fut le, amikor a OrderPlaced esemény dispatch-elésre kerül.
     * A célja, hogy a rendelés alapján frissítse a felhasználó érdeklődési embedding profilját.
     */
    public function handle(OrderPlaced $event): void
    {
        // Meghívjuk a profilfrissítőt a rendelést leadó felhasználó ID-jával
        app(UserInterestProfileUpdater::class)->update($event->userId);
    }
}
