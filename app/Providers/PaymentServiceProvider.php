<?php

namespace App\Providers;

use App\Repositories\Concretes\Transaction\TransactionRepository;
use App\Repositories\Interfaces\Transaction\TransactionRepositoryInterface;
use App\Services\Concretes\Payment\MockPaymentService;
use App\Services\Concretes\Transaction\TransactionService;
use App\Services\Interfaces\Payment\PaymentServiceInterface;
use App\Services\Interfaces\Transaction\TransactionServiceInterface;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register()
    {
       $this->registerServices();
       $this->registerRepositories();
       
    }
    
    private function registerServices() : void
    {
        $this->app->bind(TransactionServiceInterface::class, TransactionService::class);
        $this->app->bind(PaymentServiceInterface::class, MockPaymentService::class);
    }
    
    private function registerRepositories() : void
    {
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
    }
}
