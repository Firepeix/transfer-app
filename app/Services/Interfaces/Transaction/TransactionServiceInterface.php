<?php


namespace App\Services\Interfaces\Transaction;


use App\Models\Transaction\Transaction;
use App\Models\User;
use App\Notifications\Transaction\NewTransactionNotification;

interface TransactionServiceInterface
{
    public function canMakeTransaction(User $user, int $amount) : bool;
    
    public function createTransaction(User $payer, User $payee, int $amount) : Transaction;
    
    public function commitTransaction(Transaction $transaction) : void;
    
    public function createNewTransactionNotification(Transaction $transaction) : NewTransactionNotification;
}
