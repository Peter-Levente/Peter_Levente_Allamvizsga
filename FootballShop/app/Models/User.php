<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

// A felhasználókat reprezentáló modell, beépített hitelesítési képességekkel
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // A modellhez tartozó adatbázistábla neve
    protected $table = 'users';

    // Tömegesen kitölthető mezők (pl. regisztrációnál vagy admin oldalon)
    protected $fillable = [
        'email',     // Felhasználó email címe (egyedi)
        'password',  // Titkosított jelszó
        'name',      // Teljes név vagy felhasználónév
    ];

    // Olyan mezők, amelyeket elrejtünk az API válaszokban (pl. JSON)
    protected $hidden = [
        'password',
    ];

    // Típuskonverziók: a megadott mezők automatikusan átalakulnak
    protected $casts = [
        'created_at' => 'datetime', // A created_at mező DateTime objektumként használható
    ];
}
