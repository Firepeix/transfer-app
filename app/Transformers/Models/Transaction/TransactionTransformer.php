<?php


namespace App\Transformers\Models\Transaction;


use App\Models\Transaction\Transaction;
use App\Transformers\AbstractTransformer;
use App\Transformers\Models\User\UserTransformer;
use App\Transformers\Models\User\WalletTransformer;
use League\Fractal\Resource\Item;

class TransactionTransformer extends AbstractTransformer
{
    protected $availableIncludes = ['payerWallet', 'payeeWallet', 'actionUser'];
    
    public function transform(Transaction $transaction) : array
    {
        return $this->change($transaction, [
            'payerWalletId' => $transaction->getPayerWalletId(),
            'payeeWalletId' => $transaction->getPayeeWalletId(),
            'actionUserId' => $transaction->getActionUserId(),
        ]);
    }
    
    public function includeActionUser(Transaction $transaction) : Item
    {
        return $this->item($transaction->getActionUser(), new UserTransformer());
    }
    
    public function includePayerWallet(Transaction $transaction) : Item
    {
        return $this->item($transaction->getPayerWallet(), new WalletTransformer());
    }
    
    public function includePayeeWallet(Transaction $transaction) : Item
    {
        return $this->item($transaction->getPayeeWallet(), new WalletTransformer());
    }
}
