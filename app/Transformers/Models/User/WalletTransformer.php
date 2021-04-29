<?php


namespace App\Transformers\Models\User;


use App\Models\User\Wallet;
use App\Transformers\AbstractTransformer;

class WalletTransformer extends AbstractTransformer
{
    public function transform(Wallet $wallet) : array
    {
        return $this->change($wallet, [
            'balance' => $wallet->getBalance(),
        ]);
    }
}
