<?php


namespace App\Services\Concretes\Transaction;


use App\Models\Transaction\Transaction;
use App\Models\User;
use App\Notifications\Transaction\NewTransactionNotification;
use App\Services\Interfaces\Transaction\TransactionServiceInterface;

class TransactionService implements TransactionServiceInterface
{
    public function canMakeTransaction(User $user, int $amount): bool
    {
        if ($user->isStandard() && $amount > 0) {
            return $user->getWallet()->isBalanceGreaterThan($amount);
        }
        
        return false;
    }
    
    public function createTransaction(User $payer, User $payee, int $amount): Transaction
    {
        $transaction = new Transaction();
        $transaction->register($payer, $payee, $amount);
        return $transaction;
    }
    
    public function commitTransaction(Transaction $transaction) : void
    {
        $payerWallet = $transaction->getPayerWallet();
        $payeeWallet = $transaction->getPayeeWallet();
        $payerWallet->sendTo($payeeWallet, $transaction->getAmount());
    }
    
    public function createNewTransactionNotification(Transaction $transaction): NewTransactionNotification
    {
        return new NewTransactionNotification($transaction);
    }
    
}
