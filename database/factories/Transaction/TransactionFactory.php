<?php

namespace Database\Factories\Transaction;

use App\Models\Transaction\Transaction;
use App\Models\User;
use App\Models\User\Wallet;
use Database\Factories\ModelFactory;

class TransactionFactory extends ModelFactory
{
    protected $model = Transaction::class;
    
    public function definition() : array
    {
        $this->addRelation('payer', User::factory()->make());
        $this->addRelation('payee', User::factory()->make());
        return [
            'user_id' => $this->relation('actionUser', User::factory()),
            'payer_wallet_id' => $this->relation('payerWallet', Wallet::factory()),
            'payee_wallet_id' => $this->relation('payeeWallet', Wallet::factory()),
            'amount' => $this->faker->numberBetween(3000, 10000)
        ];
    }
}
