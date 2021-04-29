<?php

namespace App\Events\Notification;

use App\Events\Interfaces\Notification\NotifyInterface;
use Illuminate\Queue\SerializesModels;

class NewNotification
{
    use SerializesModels;
    
    private NotifyInterface $notification;
    
    public function __construct(NotifyInterface $notify)
    {
        $this->notification = $notify;
    }
    
    public function getNotification(): NotifyInterface
    {
        return $this->notification;
    }
}
