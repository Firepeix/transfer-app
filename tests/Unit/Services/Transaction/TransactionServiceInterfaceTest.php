<?php


namespace Services\Transaction;

use App\Exceptions\Transaction\CannotTransferToYourselfException;
use App\Exceptions\Transaction\IncorrectUserTypeToTransactionException;
use App\Exceptions\Transaction\InsufficientFundsException;
use App\Exceptions\Transaction\TransactionMustBeBiggerThanZeroException;
use App\Models\Transaction\Transaction;
use App\Models\User;
use App\Services\Interfaces\Transaction\TransactionServiceInterface;
use Tests\TestCase;

class TransactionServiceInterfaceTest extends TestCase
{
    public function testCanMakeTransactionTrue() : void
    {
        $service = app(TransactionServiceInterface::class);
        $user = User::factory()->make();
        $this->assertTrue($service->canMakeTransaction($user, 5000));
    }
    
    public function testCanMakeTransactionFalseIncorrectUserType() : void
    {
        $service = app(TransactionServiceInterface::class);
        $user = User::factory()->storeKeeper()->make();
        $this->assertFalse($service->canMakeTransaction($user, 5000));
    }
    
    public function testCanMakeTransactionFalseAmountLessThanOne() : void
    {
        $service = app(TransactionServiceInterface::class);
        $user = User::factory()->make();
        $this->assertFalse($service->canMakeTransaction($user, 0));
    }
    
    public function testCanMakeTransactionFalseAmountInsufficientFunds() : void
    {
        $service = app(TransactionServiceInterface::class);
        $user = User::factory()->walletEmpty()->make();
        $this->assertFalse($service->canMakeTransaction($user, 1));
    }
    
    public function testCreateTransactionSuccess() : void
    {
        $service = app(TransactionServiceInterface::class);
        $payer = User::factory()->make();
        $payee = User::factory()->make();
        $transaction = $service->createTransaction($payer, $payee, 5000);
        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertSame($payer->getId(), $transaction->getPayer()->getId());
        $this->assertSame($payee->getId(), $transaction->getPayee()->getId());
        $this->assertSame(5000, $transaction->getAmount());
    }
    
    public function testCreateTransactionFailSameUser() : void
    {
        $this->expectException(CannotTransferToYourselfException::class);
        $this->expectExceptionCode(CannotTransferToYourselfException::getExceptionCode());
        $service = app(TransactionServiceInterface::class);
        $payer = User::factory()->make();
        $service->createTransaction($payer, $payer, 5000);
    }
    
    public function testCreateTransactionFailIncorrectUserType() : void
    {
        $this->expectException(IncorrectUserTypeToTransactionException::class);
        $this->expectExceptionCode(IncorrectUserTypeToTransactionException::getExceptionCode());
        $service = app(TransactionServiceInterface::class);
        $payer = User::factory()->storeKeeper()->make();
        $payee = User::factory()->make();
        $service->createTransaction($payer, $payee, 5000);
    }
    
    public function testCreateTransactionFailAmountLessThanOne() : void
    {
        $this->expectException(TransactionMustBeBiggerThanZeroException::class);
        $this->expectExceptionCode(TransactionMustBeBiggerThanZeroException::getExceptionCode());
        $service = app(TransactionServiceInterface::class);
        $payer = User::factory()->make();
        $payee = User::factory()->make();
        $service->createTransaction($payer, $payee, 0);
    }
    
    public function testCreateTransactionFailAmountInsufficientFunds() : void
    {
        $this->expectException(InsufficientFundsException::class);
        $this->expectExceptionCode(InsufficientFundsException::getExceptionCode());
        $service = app(TransactionServiceInterface::class);
        $payer = User::factory()->walletEmpty()->make();
        $payee = User::factory()->make();
        $service->createTransaction($payer, $payee, 5000);
    }
    
    public function testCommitTransactionSuccess() : void
    {
        $service = app(TransactionServiceInterface::class);
        $transaction = Transaction::factory()->make();
        $fromBalance = $transaction->getPayerWallet()->getBalance();
        $toWalletBalance = $transaction->getPayeeWallet()->getBalance();
        $amount = $transaction->getAmount();
        $service->commitTransaction($transaction);
        $fromNewBalance = $transaction->getPayerWallet()->getBalance();
        $toNewWalletBalance = $transaction->getPayeeWallet()->getBalance();
        $this->assertSame($fromBalance - $amount, $fromNewBalance);
        $this->assertSame($toWalletBalance + $amount, $toNewWalletBalance);
    }
}
