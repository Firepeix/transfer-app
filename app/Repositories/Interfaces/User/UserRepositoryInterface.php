<?php


namespace App\Repositories\Interfaces\User;


use App\Models\User;
use App\Models\User\Document;
use App\Models\User\Wallet;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\Collection;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findOrFail(int $modelId): User;
    
    public function insertUser(User $user, Document $document, Wallet $wallet): void;
    
    public function updateWallet(Wallet $wallet): void;
    
    public function updateWallets(Collection $wallets): void;
    
    public function getUsers() : Collection;
}
