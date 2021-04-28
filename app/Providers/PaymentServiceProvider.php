<?php

namespace App\Providers;

use App\Services\Concretes\Payment\MockPaymentService;
use App\Services\Concretes\Transaction\TransactionService;
use App\Services\Interfaces\Payment\PaymentServiceInterface;
use App\Services\Interfaces\Transaction\TransactionServiceInterface;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(TransactionServiceInterface::class, TransactionService::class);
        $this->app->bind(PaymentServiceInterface::class, MockPaymentService::class);
    }
}
