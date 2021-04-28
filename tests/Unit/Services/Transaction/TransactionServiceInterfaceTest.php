<?php


namespace Services\Transaction;

use App\Exceptions\Transaction\CannotTransferToYourselfException;
use App\Exceptions\Transaction\IncorrectUserTypeToMakeTransactionException;
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
        $fromUser = User::factory()->make();
        $toUser = User::factory()->make();
        $transaction = $service->createTransaction($fromUser, $toUser, 5000);
        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertSame($fromUser->getId(), $transaction->getFromUser()->getId());
        $this->assertSame($toUser->getId(), $transaction->getToUser()->getId());
        $this->assertSame(5000, $transaction->getAmount());
    }
    
    public function testCreateTransactionFailSameUser() : void
    {
        $this->expectException(CannotTransferToYourselfException::class);
        $this->expectExceptionCode(CannotTransferToYourselfException::getExceptionCode());
        $service = app(TransactionServiceInterface::class);
        $fromUser = User::factory()->make();
        $service->createTransaction($fromUser, $fromUser, 5000);
    }
    
    public function testCreateTransactionFailIncorrectUserType() : void
    {
        $this->expectException(IncorrectUserTypeToMakeTransactionException::class);
        $this->expectExceptionCode(IncorrectUserTypeToMakeTransactionException::getExceptionCode());
        $service = app(TransactionServiceInterface::class);
        $fromUser = User::factory()->storeKeeper()->make();
        $toUser = User::factory()->make();
        $service->createTransaction($fromUser, $toUser, 5000);
    }
    
    public function testCreateTransactionFailAmountLessThanOne() : void
    {
        $this->expectException(TransactionMustBeBiggerThanZeroException::class);
        $this->expectExceptionCode(TransactionMustBeBiggerThanZeroException::getExceptionCode());
        $service = app(TransactionServiceInterface::class);
        $fromUser = User::factory()->make();
        $toUser = User::factory()->make();
        $service->createTransaction($fromUser, $toUser, 0);
    }
    
    public function testCreateTransactionFailAmountInsufficientFunds() : void
    {
        $this->expectException(InsufficientFundsException::class);
        $this->expectExceptionCode(InsufficientFundsException::getExceptionCode());
        $service = app(TransactionServiceInterface::class);
        $fromUser = User::factory()->walletEmpty()->make();
        $toUser = User::factory()->make();
        $service->createTransaction($fromUser, $toUser, 5000);
    }
    
    public function testCommitTransactionSuccess() : void
    {
        $service = app(TransactionServiceInterface::class);
        $transaction = Transaction::factory()->make();
        $fromBalance = $transaction->getFromWallet()->getBalance();
        $toWalletBalance = $transaction->getToWallet()->getBalance();
        $amount = $transaction->getAmount();
        $service->commitTransaction($transaction);
        $fromNewBalance = $transaction->getFromWallet()->getBalance();
        $toNewWalletBalance = $transaction->getToWallet()->getBalance();
        $this->assertSame($fromBalance - $amount, $fromNewBalance);
        $this->assertSame($toWalletBalance + $amount, $toNewWalletBalance);
    }
}
