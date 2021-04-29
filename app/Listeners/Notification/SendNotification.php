<?php

namespace App\Listeners\Notification;

use App\Events\Notification\NewNotification;
use App\Exceptions\Notification\NotificationWasNotSent;
use App\Services\Interfaces\Notification\NotificationServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Middleware\ThrottlesExceptions;

class SendNotification implements ShouldQueue
{
    private NotificationServiceInterface $service;
    public function __construct(NotificationServiceInterface $service)
    {
        $this->service = $service;
    }
    
    public function middleware(): array
    {
        $throttles = new ThrottlesExceptions(3, 7);
        return [$throttles->backoff(3)];
    }
    
    public function handle(NewNotification $event)
    {
        $notification = $event->getNotification();
        $sent = $this->service->sendNotification($notification);
        if (!$sent) {
            throw new NotificationWasNotSent($notification->getMessage());
        }
    }
}
