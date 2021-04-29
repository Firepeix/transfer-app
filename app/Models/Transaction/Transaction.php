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
    
    public function payer(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Wallet::class, 'id', 'id', 'payer_wallet_id', 'user_id');
    }
    
    public function payee(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Wallet::class, 'id', 'id', 'payee_wallet_id', 'user_id');
    }
    
    public function payerWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'payer_wallet_id');
    }
    
    public function payeeWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'payee_wallet_id');
    }
    
    public function getActionUserId() : int
    {
        return $this->user_id;
    }
    
    public function getActionUser() : User
    {
        return $this->actionUser;
    }
    
    public function getPayer(): User
    {
        return $this->payer;
    }
    
    public function getPayee(): User
    {
        return $this->payee;
    }
    
    public function getPayerWallet(): Wallet
    {
        return $this->payerWallet;
    }
    
    public function getPayerWalletId(): int
    {
        return $this->payer_wallet_id;
    }
    
    public function getPayeeWallet(): Wallet
    {
        return $this->payeeWallet;
    }
    
    public function getPayeeWalletId(): int
    {
        return $this->payee_wallet_id;
    }
    
    public function getAmount(): int
    {
        return $this->amount;
    }
    
    public function register(User $payer, User $payee, int $amount): void
    {
        $payerWallet           = $payer->getWallet();
        $payeeWallet           = $payee->getWallet();
        $this->payer_wallet_id = $payerWallet->getId();
        $this->user_id         = $payer->getId();
        $this->payee_wallet_id = $payeeWallet->getId();
        $this->amount          = $amount;
        $this->setRelation('payer', $payer);
        $this->setRelation('actionUser', $payer);
        $this->setRelation('payee', $payee);
        $this->setRelation('payerWallet', $payerWallet);
        $this->setRelation('payeeWallet', $payeeWallet);
        $this->validate();
    }
    
    public function validate(): void
    {
        $exception = new IncorrectUserTypeToMakeTransactionException($this->payer);
        throw_if(!$this->payer->isStandard(), $exception);
        $exception = new CannotTransferToYourselfException();
        throw_if($this->payer->getId() === $this->payee->getId(), $exception);
        $exception = new TransactionMustBeBiggerThanZeroException();
        throw_if($this->amount < 1, $exception);
        $exception = new InsufficientFundsException($this->payer);
        throw_if(!$this->payerWallet->isBalanceGreaterThan($this->amount), $exception);
    }
}
