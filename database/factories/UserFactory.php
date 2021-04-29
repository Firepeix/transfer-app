<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\User\Document;
use App\Models\User\Wallet;
use Illuminate\Support\Str;

class UserFactory extends ModelFactory
{
    protected $model = User::class;
    
    public function definition() : array
    {
        $this->addRelation('wallet', Wallet::factory()->make(), 'user_id');
        $this->addRelation('document', Document::factory()->make(), 'user_id');
        
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'type' => User::STANDARD,
            'remember_token' => Str::random(10)
        ];
    }

    public function storeKeeper() : self
    {
        return $this->state(function () {
            $this->addRelation('document', Document::factory()->cnpj()->make());
            return [
                'type' => User::STORE_KEEPER,
            ];
        });
    }
    
    public function walletEmpty() : self
    {
        return $this->state(function () {
            $this->addRelation('wallet', Wallet::factory()->empty()->make());
            return [
            ];
        });
    }
    
}
