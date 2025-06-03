<?php

namespace App\Listeners;

use App\Events\ProductViewed;
use App\Models\ProductView;
use App\Services\UserInterestProfileUpdater;
use Illuminate\Support\Facades\Cache;

// Ez a listener figyeli a ProductViewed eseményt,
// és frissíti a felhasználói érdeklődési profilt minden termékmegtekintés után
class UpdateUserProfileOnView
{
    /**
     * Ez a metódus automatikusan lefut, amikor a ProductViewed eseményt dispatch-elik.
     *
     * @param ProductViewed $event Az esemény, amely tartalmazza a user ID-t és a termék ID-t
     * @return void
     */
    public function handle(ProductViewed $event): void
    {
        // Cache-ben tárolt számláló inicializálása, ha még nem létezik
        Cache::rememberForever('product_view_counter', fn() => 0);

        // A számláló növelése minden egyes megtekintésnél
        $count = Cache::increment('product_view_counter');

        // Minden 50. megtekintésnél: töröljük a 90 napnál régebbi megtekintési adatokat
        if ($count % 50 === 0) {
            ProductView::where('created_at', '<', now()->subDays(90))->delete();
        }

        // A megtekintés naplózása az adatbázisba
        ProductView::create([
            'user_id' => $event->userId,
            'product_id' => $event->productId,
        ]);

        // A felhasználó érdeklődési profiljának frissítése a megtekintés alapján
        app(UserInterestProfileUpdater::class)->update($event->userId);
    }
}
