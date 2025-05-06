<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\ProductViewed::class => [
            \App\Listeners\UpdateUserProfileOnView::class,
        ],
        \App\Events\ProductAddedToCart::class => [
            \App\Listeners\UpdateUserProfileOnCartAdd::class,
        ],
        \App\Events\OrderPlaced::class => [
            \App\Listeners\UpdateUserProfileOnOrder::class,
        ],
    ];
}
