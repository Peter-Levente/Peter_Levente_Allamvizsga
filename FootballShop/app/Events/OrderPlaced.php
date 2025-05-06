<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// A rendelés leadását reprezentáló eseményosztály
class OrderPlaced
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // A felhasználó azonosítója, aki a rendelést leadta
    public int $userId;

    // Konstruktor, amely átveszi a user ID-t, és eltárolja a példányban
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }
}
