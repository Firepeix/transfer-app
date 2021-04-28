<?php


namespace App\Exceptions\Transaction;


use App\Exceptions\AbstractBaseException;
use Throwable;

class TransactionException extends AbstractBaseException
{
    private const CODE = 1;
    
    public function __construct(? string $message, Throwable $previous = null)
    {
        $message = $message ?? 'Erro genérico de transação';
        parent::__construct($message, $previous);
    }
    
    public static function getExceptionCode() : int
    {
        return self::CODE;
    }
    
}
