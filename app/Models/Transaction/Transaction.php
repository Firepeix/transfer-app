<?php

namespace App\Models\Transaction;

use App\Exceptions\Transaction\CannotTransferToYourselfException;
use App\Exceptions\Transaction\IncorrectUserTypeToMakeTransactionException;
use App\Exceptions\Transaction\InsufficientFundsException;
use App\Exceptions\Transaction\TransactionMustBeBiggerThanZeroException;
use App\Models\AbstractModel;
use App\Models\User;
use App\Models\User\Wallet;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Transaction extends AbstractModel
{
    public function actionUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function fromUser(): HasOneThrough
    {
        $localKey = 'from_wallet_id';
        return $this->hasOneThrough(User::class, Wallet::class, $localKey);
    }
    
    public function toUser(): HasOneThrough
    {
        $localKey = 'to_wallet_id';
        return $this->hasOneThrough(User::class, Wallet::class, $localKey);
    }
    
    public function fromWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'from_wallet_id');
    }
    
    public function toWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'to_wallet_id');
    }
    
    public function getFromUser(): User
    {
        return $this->fromUser;
    }
    
    public function getToUser(): User
    {
        return $this->toUser;
    }
    
    public function getFromWallet(): Wallet
    {
        return $this->fromWallet;
    }
    
    public function getToWallet(): Wallet
    {
        return $this->toWallet;
    }
    
    public function getAmount() : int
    {
        return $this->amount;
    }
    
    public function register(User $fromUser, User $toUser, int $amount): void
    {
        $fromWallet = $fromUser->getWallet();
        $toWallet = $fromUser->getWallet();
        $this->from_wallet_id = $fromWallet->getId();
        $this->to_wallet_id = $toWallet->getId();
        $this->amount = $amount;
        $this->setRelation('fromUser', $fromUser);
        $this->setRelation('toUser', $toUser);
        $this->setRelation('fromWallet', $fromWallet);
        $this->setRelation('toWallet', $toWallet);
        $this->validate();
    }
    
    public function validate(): void
    {
        $exception = new IncorrectUserTypeToMakeTransactionException($this->fromUser);
        throw_if(!$this->fromUser->isStandard(), $exception);
        $exception = new CannotTransferToYourselfException();
        throw_if($this->fromUser->getId() === $this->toUser->getId(), $exception);
        $exception = new TransactionMustBeBiggerThanZeroException();
        throw_if($this->amount < 1, $exception);
        $exception = new InsufficientFundsException($this->fromUser);
        throw_if(!$this->fromWallet->isBalanceGreaterThan($this->amount), $exception);
    }
}
