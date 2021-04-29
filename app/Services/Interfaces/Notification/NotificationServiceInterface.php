<?php


namespace App\Services\Interfaces\Notification;

use App\Events\Interfaces\Notification\NotifyInterface;
use App\Models\Notification\Notification;

interface NotificationServiceInterface
{
    public function dispatchNotification(NotifyInterface $notify) : void;
    
    public function sendNotification(NotifyInterface $notify) : bool;
    
    public function createNotification(NotifyInterface $notify) : Notification;
}
