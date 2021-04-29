<?php


namespace App\Exceptions\Integration;


use App\Exceptions\AbstractBaseException;
use Throwable;

class IntegrationException extends AbstractBaseException
{
    private const CODE = 2;
    
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
