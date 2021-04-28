<?php


namespace App\Services\Concretes\Transaction;


use App\Models\Transaction\Transaction;
use App\Models\User;
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
    
    public function createTransaction(User $fromUser, User $toUser, int $amount): Transaction
    {
        $transaction = new Transaction();
        $transaction->register($fromUser, $toUser, $amount);
        return $transaction;
    }
    
    public function commitTransaction(Transaction $transaction) : void
    {
        $fromWallet = $transaction->getFromWallet();
        $toWallet = $transaction->getToWallet();
        $fromWallet->sendTo($toWallet, $transaction->getAmount());
    }
}
