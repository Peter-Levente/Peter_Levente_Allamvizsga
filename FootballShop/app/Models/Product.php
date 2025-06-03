<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Termékmodell — a webshopban elérhető termékek tárolására szolgál
class Product extends Model
{
    // Az adatbázisban használt tábla neve
    protected $table = 'products';

    // Tömegesen kitölthető mezők (pl. create() vagy update() használatakor)
    protected $fillable = [
        'name',         // A termék neve
        'price',        // A termék ára
        'category',     // A termék kategóriája (pl. Cipők, Mezek stb.)
        'description',  // A termék részletes leírása
        'image'         // A termékhez tartozó kép elérési útja vagy fájlneve
    ];
}
