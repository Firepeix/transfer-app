<?php

namespace App\Policies\Transaction;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;
    
    
    public function transfer(User $loggedUser, User $payer) : bool
    {
        return $loggedUser->getId() === $payer->getId() && $payer->isStandard();
    }
}
