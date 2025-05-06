<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductViewed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // A felhasználó azonosítója, aki megtekintette a terméket
    public int $userId;

    // A megtekintett termék azonosítója
    public int $productId;

    // Konstruktor: amikor az eseményt példányosítjuk, megkapja a user ID-t és a product ID-t
    public function __construct(int $userId, int $productId)
    {
        $this->userId = $userId;
        $this->productId = $productId;
    }
}
