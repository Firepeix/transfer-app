<?php


namespace App\Repositories\Concretes\User;


use App\Models\AbstractModel;
use App\Models\User;
use App\Models\User\Wallet;
use App\Repositories\Concretes\AbstractRepository;
use App\Repositories\Interfaces\User\UserRepositoryInterface;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    protected function getModel(): AbstractModel
    {
        return new User();
    }
    
    public function updateWallet(Wallet $wallet): void
    {
        $this->save($wallet);
    }
}
