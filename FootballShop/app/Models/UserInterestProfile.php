<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// A felhasználó érdeklődési profilját tároló modell (pl. embedding a viselkedés alapján)
class UserInterestProfile extends Model
{
    // Tömegesen kitölthető mezők
    protected $fillable = [
        'user_id',    // A felhasználó azonosítója
        'embedding'   // Az érdeklődési profil embedding (pl. PostgreSQL vector típus)
    ];

    /**
     * Kapcsolat a felhasználóval
     * Egy profil pontosan egy felhasználóhoz tartozik
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
