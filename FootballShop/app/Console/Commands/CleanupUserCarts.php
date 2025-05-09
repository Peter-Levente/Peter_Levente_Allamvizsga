<?php

// app/Console/Commands/CleanupUserCarts.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cart;
use Carbon\Carbon;

class CleanupUserCarts extends Command
{
    protected $signature = 'cart:cleanup-old';
    protected $description = 'Törli az 1 napnál régebbi kosár elemeket minden felhasználótól';

    public function handle()
    {
        $deleted = Cart::where('created_at', '<', Carbon::now()->subDay())->delete();

        $this->info("Törölve: {$deleted} régi kosár elem.");
    }
}
