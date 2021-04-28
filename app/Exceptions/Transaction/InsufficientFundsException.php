<?php


namespace App\Exceptions\Transaction;


use App\Models\User;
use Throwable;

class InsufficientFundsException extends TransactionException
{
    private const CODE = 3;
    
    public function __construct(User $user, Throwable $previous = null)
    {
        $name = $user->getFullName();
        $message = "Usuário $name não tem saldo suficiente para essa transferência";
        parent::__construct($message, $previous);
    }
    
    public static function getExceptionCode(): int
    {
        return parent::getExceptionCode() . self::CODE;
    }
}
