<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    // Engedélyezett mezők tömeges kitöltéshez
    protected $fillable = ['name', 'price', 'category', 'description', 'image'];

    // Kategória szerinti termékek lekérdezése
    public static function getProductsByCategory($category)
    {
        return self::where('category', $category)->get();
    }
}
