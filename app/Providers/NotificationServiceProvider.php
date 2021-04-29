<?php

namespace App\Providers;

use App\Services\Concretes\Notification\MockNotificationService;
use App\Services\Interfaces\Notification\NotificationServiceInterface;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(NotificationServiceInterface::class, MockNotificationService::class);
    }
}
