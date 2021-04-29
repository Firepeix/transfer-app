<?php

namespace App\Providers;

use App\Events\Notification\NewNotification;
use App\Listeners\Notification\SendNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        NewNotification::class => [
            SendNotification::class,
        ],
    ];
}
