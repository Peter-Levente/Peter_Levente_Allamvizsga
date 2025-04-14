<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Embedding extends Model
{
    use HasFactory;
    protected $connection = 'pgsql'; // PostgreSQL kapcsolat használata
    protected $table = 'embeddings';

    protected $fillable = [
        'context',
        'content',
        'embedding',
        'related_id'
    ];
}
