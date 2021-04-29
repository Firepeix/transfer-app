<?php


namespace App\Exceptions\Notification;

use App\Exceptions\AbstractBaseException;
use Throwable;

class NotificationException extends AbstractBaseException
{
    private const CODE = 3;
    
    public function __construct(? string $message, Throwable $previous = null)
    {
        $message = $message ?? 'Erro de conexão por favor tente mais tarde!';
        parent::__construct($message, $previous);
    }
    
    public static function getExceptionCode() : int
    {
        return self::CODE;
    }
    
}
