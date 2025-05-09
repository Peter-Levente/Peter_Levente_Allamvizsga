<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\CleanupUserCarts;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        CleanupUserCarts::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cart:cleanup-old')->dailyAt('12:00');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
