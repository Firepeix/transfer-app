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
        return [
            'user_id' => $this->relation('actionUser', User::factory()),
            'from_wallet_id' => $this->relation('fromWallet', Wallet::factory()),
            'to_wallet_id' => $this->relation('toWallet', Wallet::factory()),
            'amount' => $this->faker->numberBetween(3000, 10000)
        ];
    }
}
