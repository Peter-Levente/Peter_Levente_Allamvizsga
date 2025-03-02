<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Az alapértelmezett táblanév az osztály nevéből származik, tehát itt a 'products' táblát fogja keresni
    protected $table = 'products';

    // Az oszlopok, amelyek masszív tömeges hozzárendeléshez engedélyezettek
    protected $fillable = ['name', 'price', 'image', 'category', 'size'];

    // Esetleg hozzáadhatunk egy egyéni kapcsoló metódust a kategória alapján való szűréshez
    public static function getProductsByCategory($category)
    {
        return self::where('category', $category)->get();  // Lekérdezi az összes terméket a kategória alapján
    }
}
