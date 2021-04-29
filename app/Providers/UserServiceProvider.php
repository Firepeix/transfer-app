<?php

namespace App\Providers;

use App\Repositories\Concretes\User\UserRepository;
use App\Repositories\Interfaces\User\UserRepositoryInterface;
use App\Services\Concretes\User\UserService;
use App\Services\Interfaces\User\UserServiceInterface;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
       $this->registerServices();
       $this->registerRepositories();
       
    }
    
    private function registerServices() : void
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }
    
    private function registerRepositories() : void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}
