<?php

namespace App\Listeners\Notification;

use App\Events\Notification\NewNotification;
use App\Exceptions\Notification\NotificationWasNotSent;
use App\Repositories\Interfaces\Notification\NotificationRepositoryInterface;
use App\Services\Interfaces\Notification\NotificationServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Middleware\ThrottlesExceptions;

class SendNotification implements ShouldQueue
{
    private NotificationServiceInterface $service;
    private NotificationRepositoryInterface $repository;
    public function __construct(NotificationServiceInterface $service, NotificationRepositoryInterface $repository)
    {
        $this->service = $service;
        $this->repository = $repository;
    }
    
    public function middleware(): array
    {
        $throttles = new ThrottlesExceptions(3, 7);
        return [$throttles->backoff(3)];
    }
    
    public function handle(NewNotification $event)
    {
        $notify = $event->getNotification();
        $sent = $this->service->sendNotification($notify);
        if (!$sent) {
            throw new NotificationWasNotSent($notify->getMessage());
        }
        $notification = $this->service->createNotification($notify);
        $this->repository->insert($notification);
    }
}
