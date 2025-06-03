<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// A kosár modell, amely a "cart" táblához kapcsolódik
class Cart extends Model
{
    use HasFactory;

    // Az adatbázisban használt tábla neve
    protected $table = 'cart';

    // Az elsődleges kulcs (alapértelmezés szerint "id", de itt külön is megadjuk)
    protected $primaryKey = 'id';

    // Azok a mezők, amelyek tömegesen kitölthetők (pl. create(), update() során)
    protected $fillable = ['user_id', 'product_id', 'quantity'];

    /**
     * Kapcsolat a felhasználóhoz (user_id alapján)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Kapcsolat a termékhez (product_id alapján)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
