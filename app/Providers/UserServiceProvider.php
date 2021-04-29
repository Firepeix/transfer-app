<?php

namespace App\Providers;

use App\Repositories\Concretes\User\UserRepository;
use App\Repositories\Interfaces\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
       $this->registerRepositories();
       
    }
    
    private function registerRepositories() : void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}
