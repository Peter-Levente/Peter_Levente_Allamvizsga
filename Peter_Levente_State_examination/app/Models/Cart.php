<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart'; // Tábla neve
    protected $fillable = ['user_id', 'product_id', 'quantity']; // Engedélyezett mezők tömbje

    // Kapcsolat a User modellel
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Kapcsolat a Product modellel
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
