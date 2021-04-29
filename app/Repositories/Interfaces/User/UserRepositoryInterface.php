<?php


namespace App\Repositories\Interfaces\User;


use App\Models\User\Wallet;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\Collection;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function updateWallet(Wallet $wallet) : void;
    public function updateWallets(Collection $wallets) : void;
}
