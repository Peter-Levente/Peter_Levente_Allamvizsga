<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInterestProfile extends Model
{

    protected $fillable = ['user_id', 'embedding'];

    protected $casts = [
        'embedding' => 'array', // ha nem PGVector típus, hanem JSON/text
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
