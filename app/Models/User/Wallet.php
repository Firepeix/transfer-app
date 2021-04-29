<?php

namespace App\Models\User;

use App\Models\AbstractModel;
use App\Models\User;

class Wallet extends AbstractModel
{
    public function register(User $user) : void
    {
        $this->user_id = $user->getId();
        $this->amount = 0;
        $this->setRelation('user', $user);
    }
    
    public function commitToUser(User $user) : void
    {
        $this->user_id = $user->getId();
        $this->setRelation('user', $user);
    }
    
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
