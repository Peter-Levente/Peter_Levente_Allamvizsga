<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Egy rendeléshez tartozó tétel (termék, mennyiség, ár)
class OrderItem extends Model
{
    use HasFactory;

    // Tömegesen kitölthető mezők, például Order::create() vagy OrderItem::create() esetén
    protected $fillable = [
        'order_id',     // A rendelés azonosítója, amelyhez ez a tétel tartozik
        'product_id',   // A termék azonosítója
        'quantity',     // A megvásárolt mennyiség
        'price'         // Az aktuális egységár (a rendelés időpontjában)
    ];

    /**
     * Kapcsolat az Order modellel
     * Minden OrderItem egy rendeléshez tartozik
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Kapcsolat a Product modellel
     * Minden OrderItem egy adott terméket reprezentál
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
