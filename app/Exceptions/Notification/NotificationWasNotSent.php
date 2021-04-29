<?php


namespace App\Exceptions\Notification;


use Throwable;

class NotificationWasNotSent extends NotificationException
{
    private const CODE = 1;
    
    public function __construct(string $notificationMessage, Throwable $previous = null)
    {
        $message = "A notificação \"$notificationMessage\" não foi enviada";
        parent::__construct($message, $previous);
    }
    
    public static function getExceptionCode(): int
    {
        return parent::getExceptionCode() . self::CODE;
    }
}
