<?php

namespace App\Providers;

use App\Repositories\Concretes\Notification\NotificationRepository;
use App\Repositories\Interfaces\Notification\NotificationRepositoryInterface;
use App\Services\Concretes\Notification\MockNotificationService;
use App\Services\Interfaces\Notification\NotificationServiceInterface;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerServices();
        $this->registerRepositories();
    }
    
    private function registerServices() : void
    {
        $this->app->bind(NotificationServiceInterface::class, MockNotificationService::class);
    }
    
    private function registerRepositories() : void
    {
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
    }
}
