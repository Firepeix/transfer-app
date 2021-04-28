<?php

namespace Database\Factories\User;

use App\Models\User\Wallet;
use Database\Factories\ModelFactory;

class WalletFactory extends ModelFactory
{
    protected $model = Wallet::class;
    
    public function definition() : array
    {
        return [
            'amount' => $this->faker->numberBetween(50000, 800000)
        ];
    }
    
    public function empty() : self
    {
        return $this->state(function () {
            return [
                'amount' => 0
            ];
        });
    }
}
