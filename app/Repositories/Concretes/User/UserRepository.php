<?php


namespace App\Repositories\Concretes\User;


use App\Models\AbstractModel;
use App\Models\User;
use App\Models\User\Document;
use App\Models\User\Wallet;
use App\Repositories\Concretes\AbstractRepository;
use App\Repositories\Interfaces\User\UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    protected function getModel(): AbstractModel
    {
        return new User();
    }
    
    public function findOrFail(int $modelId): User
    {
        return parent::findOrFail($modelId);
    }
    
    
    public function insertUser(User $user, Document $document, Wallet $wallet): void
    {
        $this->save($user);
        $document->commitToUser($user);
        $this->save($document);
        $wallet->commitToUser($user);
        $this->save($wallet);
    }
    
    
    public function updateWallet(Wallet $wallet): void
    {
        $this->save($wallet);
    }
    
    public function updateWallets(Collection $wallets): void
    {
        $wallets->each(function (Wallet $wallet) {
            $this->updateWallet($wallet);
        });
    }
    
    public function getUsers(): Collection
    {
        return User::with($this->getDefaultIncludes())->get();
    }
    
    protected function getDefaultIncludes(): array
    {
        return ['wallet', 'document'];
    }
}
