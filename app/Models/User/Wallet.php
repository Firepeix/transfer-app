<?php

namespace App\Models\User;

use App\Models\AbstractModel;

class Wallet extends AbstractModel
{
    public function isBalanceGreaterThan(int $amount) : bool
    {
        return $this->amount > $amount;
    }
    
    public function getBalance() : int
    {
        return $this->amount;
    }
    
    public function sendTo(Wallet $wallet, int $amount) : void
    {
        $this->amount = $this->amount - $amount;
        $wallet->amount = $wallet->amount + $amount;
    }
}
