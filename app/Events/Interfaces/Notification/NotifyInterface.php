<?php


namespace App\Events\Interfaces\Notification;


use App\Models\User;

interface NotifyInterface
{
    public function getToUser(): User;
    
    public function getMessage(): string;
    
    public function getType(): int;
}
