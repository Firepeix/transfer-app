<?php


namespace App\Services\Interfaces\Payment;


use App\Models\Transaction\Transaction;

interface PaymentServiceInterface
{
    public function isAuthorized(Transaction $transaction) : bool;
}
