<?php


namespace App\Services\Interfaces\User;


use App\Models\User;
use App\Models\User\Document;
use App\Models\User\Wallet;

interface UserServiceInterface
{
    public function createUser(string $name, string $email, string $password, string $type) : User;
    
    public function updateUser(User $user, ?string $name, ? string $password) : void;
    
    public function createDocument(User $user, string $document) : Document;
    
    public function createWallet(User $user) : Wallet;
}
