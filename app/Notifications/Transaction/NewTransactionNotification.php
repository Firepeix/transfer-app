<?php

namespace App\Notifications\Transaction;

use App\Events\Interfaces\Notification\NotifyInterface;
use App\Models\Transaction\Transaction;
use App\Models\User;
use App\Primitives\NumberPrimitive;

class NewTransactionNotification implements NotifyInterface
{
    public const NEW_TRANSACTION = 1;
    private string $message;
    private User $toUser;
    
    public function __construct(Transaction $transaction)
    {
        $amount = app(NumberPrimitive::class)::toReal($transaction->getAmount());
        $name = $transaction->getPayer()->getFullName();
        $this->message = "Que bom! Você acabou de receber R$ $amount de $name";
        $this->toUser  = $transaction->getPayee();
    }
    
    public function getToUser(): User
    {
        return $this->toUser;
    }
    
    public function getMessage(): string
    {
        return $this->message;
    }
    
    public function getType(): int
    {
        return self::NEW_TRANSACTION;
    }
}
