<?php


namespace Models\User;


use App\Models\User\Wallet;
use Tests\TestCase;

class WalletTest extends TestCase
{
    public function testIsBalanceGreaterThanTrue(): void
    {
        $wallet = Wallet::factory()->make();
        $this->assertTrue($wallet->isBalanceGreaterThan(0));
    }
    
    public function testIsBalanceGreaterThanFalse(): void
    {
        $wallet = Wallet::factory()->empty()->make();
        $this->assertFalse($wallet->isBalanceGreaterThan(0));
    }
    
    public function testSendTo(): void
    {
        $fromWallet = Wallet::factory()->make();
        $toWallet   = Wallet::factory()->make();
        $fromAmount   = $fromWallet->getBalance();
        $toAmount   = $toWallet->getBalance();
        $amount     = 5000;
        $fromWallet->sendTo($toWallet, $amount);
        $this->assertSame($fromAmount - $amount, $fromWallet->getBalance());
        $this->assertSame($toAmount + $amount, $toWallet->getBalance());
    }
}
