<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// Eseményosztály, amely akkor aktiválódik, amikor egy felhasználó megtekint egy terméket
class ProductViewed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // A felhasználó azonosítója, aki a megtekintést végrehajtotta
    public int $userId;

    // A megtekintett termék azonosítója
    public int $productId;

    /**
     * Konstruktor
     *
     * A példányosításkor a konstruktor eltárolja a felhasználó és a termék azonosítóját.
     *
     * @param int $userId A felhasználó azonosítója
     * @param int $productId A megtekintett termék azonosítója
     */
    public function __construct(int $userId, int $productId)
    {
        $this->userId = $userId;
        $this->productId = $productId;
    }
}
