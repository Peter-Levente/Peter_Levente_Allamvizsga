<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Az Order modell a rendelések tárolásához és kezeléséhez
class Order extends Model
{
    use HasFactory;

    // Tömegesen kitölthető mezők
    protected $fillable = [
        'user_id',         // A rendelést leadó felhasználó azonosítója
        'address',         // Szállítási cím
        'total_amount',    // A rendelés végösszege
        'payment_method',  // Fizetési mód (pl. utánvét, bankkártya)
        'phone',           // Telefonszám
        'status',          // Rendelés státusza (pl. pending, completed, cancelled)
    ];

    /**
     * Kapcsolat a felhasználóval (user_id alapján)
     * Egy rendeléshez egy felhasználó tartozik
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Kapcsolat a rendelési tételekkel (order_items)
     * Egy rendeléshez több tétel tartozhat
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
