<?php


namespace Services\Payment;


use App\Models\Transaction\Transaction;
use App\Services\Interfaces\Payment\PaymentServiceInterface;
use Tests\TestCase;

class PaymentServiceInterfaceTest extends TestCase
{
    public function testIsAuthorized() : void
    {
        $service = app(PaymentServiceInterface::class);
        $transaction = Transaction::factory()->make();
        $this->assertIsBool($service->isAuthorized($transaction));
    }
}
