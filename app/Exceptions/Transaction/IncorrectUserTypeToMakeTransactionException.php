<?php


namespace App\Exceptions\Transaction;


use App\Models\User;
use Throwable;

class IncorrectUserTypeToMakeTransactionException extends TransactionException
{
    private const CODE = 1;
    
    public function __construct(User $user, Throwable $previous = null)
    {
        $name = $user->getFullName();
        $message = "Usuário $name não pode fazer transferências";
        $reason = "devido a ser do tipo lojista";
        parent::__construct("$message $reason", $previous);
    }
    
    public static function getExceptionCode(): int
    {
        return parent::getExceptionCode() . self::CODE;
    }
}
