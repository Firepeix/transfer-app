<?php


namespace App\Exceptions\Transaction;


use Throwable;

class TransactionMustBeBiggerThanZeroException extends TransactionException
{
    private const CODE = 2;
    
    public function __construct(Throwable $previous = null)
    {
        $message = "O valor de uma transferência deve ser maior que 0";
        parent::__construct($message, $previous);
    }
    
    public static function getExceptionCode(): int
    {
        return parent::getExceptionCode() . self::CODE;
    }
}
