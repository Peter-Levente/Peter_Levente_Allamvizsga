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

    /**
     * Konstruktor
     *
     * Ez a metódus akkor hívódik meg, amikor az esemény példányosításra kerül.
     * Itt kapjuk meg annak a felhasználónak az ID-ját, aki leadta a rendelést.
     *
     * @param int $userId A felhasználó azonosítója
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }
}
