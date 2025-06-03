<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// Eseményosztály, amely akkor kerül meghívásra, amikor egy felhasználó terméket ad a kosarához
class ProductAddedToCart
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // A felhasználó azonosítója, aki a kosárba helyezést végrehajtotta
    public int $userId;

    /**
     * Konstruktor
     *
     * Példányosításkor eltárolja a felhasználó azonosítóját az osztályban.
     *
     * @param int $userId A felhasználó azonosítója
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }
}
