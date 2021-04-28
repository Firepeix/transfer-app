<?php


namespace App\Exceptions\Transaction;


use Throwable;

class CannotTransferToYourselfException extends TransactionException
{
    private const CODE = 4;
    
    public function __construct(Throwable $previous = null)
    {
        $message = "Não se pode fazer transferências para si mesmo";
        parent::__construct($message, $previous);
    }
    
    public static function getExceptionCode(): int
    {
        return parent::getExceptionCode() . self::CODE;
    }
}
