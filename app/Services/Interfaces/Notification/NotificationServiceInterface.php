<?php


namespace App\Services\Interfaces\Notification;

use App\Events\Interfaces\Notification\NotifyInterface;

interface NotificationServiceInterface
{
    public function dispatchNotification(NotifyInterface $notify) : void;
    
    public function sendNotification(NotifyInterface $notify) : bool;
}
