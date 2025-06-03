<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Embedding modell — különféle szövegekhez kapcsolódó vektorreprezentációk tárolására szolgál
class Embedding extends Model
{
    use HasFactory;

    // Az adatbázisban szereplő tábla neve
    protected $table = 'embeddings';

    // Tömegesen kitölthető mezők (pl. create() vagy update() használatával)
    protected $fillable = [
        'context',     // Milyen típusú adat embeddingje (pl. "product", "faq", "review")
        'content',     // Az eredeti szöveg, amelyből az embedding készült
        'embedding',   // A tényleges vektormező (PostgreSQL VECTOR típusként tárolva)
        'related_id'   // Az adott szöveghez kapcsolódó rekord ID-ja (pl. product_id)
    ];
}
