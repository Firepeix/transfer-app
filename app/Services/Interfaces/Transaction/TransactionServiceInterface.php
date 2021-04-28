<?php


namespace App\Services\Interfaces\Transaction;


use App\Models\Transaction\Transaction;
use App\Models\User;

interface TransactionServiceInterface
{
    public function canMakeTransaction(User $user, int $amount) : bool;
    
    public function createTransaction(User $fromUser, User $toUser, int $amount) : Transaction;
    
    public function commitTransaction(Transaction $transaction) : void;
}
