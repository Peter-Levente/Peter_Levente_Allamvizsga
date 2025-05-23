<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // Azokat az oszlopokat engedélyezzük, amelyeket tömeges feltöltéssel elérhetünk
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
    ];

    // Kapcsolat az Order modellel
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Kapcsolat a Product modellel
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

