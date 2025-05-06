<?php

namespace App\Listeners;

use App\Events\ProductViewed;
use App\Models\ProductView;
use App\Services\UserInterestProfileUpdater;
use Illuminate\Support\Facades\Cache;

class UpdateUserProfileOnView
{
    /**
     * Ez a metódus hajtódik végre, amikor a ProductViewed esemény dispatch-elésre kerül.
     */
    public function handle(ProductViewed $event): void
    {
        // A cache-ben tárolt számláló inicializálása, ha még nem létezik
        Cache::rememberForever('product_view_counter', fn() => 0);

        // A számláló növelése minden egyes megtekintésnél
        $count = Cache::increment('product_view_counter');

        // Minden 50. megtekintés után töröljük a 90 napnál régebbi product_views sorokat
        if ($count % 50 === 0) {
            ProductView::where('created_at', '<', now()->subDays(90))->delete();
        }

//        KESOBB TESZTELD EZT A FELSOT

        // A megtekintés rögzítése az adatbázisba
        ProductView::create([
            'user_id' => $event->userId,
            'product_id' => $event->productId,
        ]);

        // A felhasználói profil frissítése az új megtekintés alapján
        app(UserInterestProfileUpdater::class)->update($event->userId);
    }



}
