<?php


namespace App\Exceptions\User;

use App\Exceptions\AbstractBaseException;
use Throwable;

class UserException extends AbstractBaseException
{
    private const CODE = 5;
    
    public function __construct(? string $message, Throwable $previous = null)
    {
        $message = $message ?? 'Usuário invalido!';
        parent::__construct($message, $previous);
    }
    
    public static function getExceptionCode() : int
    {
        return self::CODE;
    }
    
}
