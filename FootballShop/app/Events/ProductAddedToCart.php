<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductAddedToCart
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // Az esemény egyetlen nyilvános adattagja: a felhasználó azonosítója
    public int $userId;

    // Konstruktor, amely átveszi és eltárolja a felhasználó azonosítóját
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }
}
