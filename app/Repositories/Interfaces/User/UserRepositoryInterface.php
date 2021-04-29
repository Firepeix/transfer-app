<?php


namespace App\Repositories\Interfaces\User;


use App\Models\User\Wallet;
use App\Repositories\Interfaces\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function updateWallet(Wallet $wallet) : void;
}
