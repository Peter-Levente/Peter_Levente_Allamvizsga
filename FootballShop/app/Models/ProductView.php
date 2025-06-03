<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// A ProductView modell a felhasználók által megtekintett termékeket naplózza
class ProductView extends Model
{
    // Nem használjuk az Eloquent által automatikusan kezelt created_at és updated_at mezőket
    public $timestamps = false;

    // Engedélyezett mezők tömeges kitöltéshez
    protected $fillable = [
        'user_id',     // A felhasználó azonosítója, aki megtekintette a terméket
        'product_id',  // A megtekintett termék azonosítója
        'viewed_at'    // A megtekintés időpontja (opcionális, ha külön rögzítve van)
    ];

    /**
     * Kapcsolat a felhasználóval
     * Egy megtekintés egy felhasználóhoz tartozik
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Kapcsolat a termékkel
     * Egy megtekintés egy termékhez tartozik
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
