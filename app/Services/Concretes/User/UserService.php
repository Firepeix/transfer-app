<?php


namespace App\Services\Concretes\User;



use App\Models\User;
use App\Models\User\Document;
use App\Models\User\Wallet;
use App\Services\Interfaces\User\UserServiceInterface;

class UserService implements UserServiceInterface
{
    public function createUser(string $name, string $email, string $password, string $type): User
    {
        $user = new User();
        $user->register($name, $email, $password, $type);
        return $user;
    }
    
    public function updateUser(User $user, ?string $name, ?string $password): void
    {
        $user->setFullName($name ?? $user->getFullName());
        $user->setPassword($password ?? $user->getPassword());
    }
    
    public function createDocument(User $user, string $document): Document
    {
        $document = new Document();
        $document->register($user, $document);
        return $document;
    }
    
    public function createWallet(User $user): Wallet
    {
        $wallet = new Wallet();
        $wallet->register($user);
        return $wallet;
    }
    
}
