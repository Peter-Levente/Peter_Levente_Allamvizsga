<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart'; // Ha az adatbázisban tényleg "cart" a neve
    protected $primaryKey = 'id'; // Az elsődleges kulcs
    protected $fillable = ['user_id', 'product_id', 'quantity']; // Engedélyezett mezők

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
