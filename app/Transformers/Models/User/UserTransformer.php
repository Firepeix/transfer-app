<?php


namespace App\Transformers\Models\User;


use App\Models\User;
use App\Transformers\AbstractTransformer;
use League\Fractal\Resource\Item;

class UserTransformer extends AbstractTransformer
{
    protected $availableIncludes = ['wallet', 'document'];
    
    public function transform(User $user) : array
    {
        return $this->change($user, [
            'name' => $user->getFullName(),
            'email' => $user->getEmail(),
            'type' => $user->getType()
        ]);
    }
    
    public function includeWallet(User $user) : Item
    {
        return $this->item($user->getWallet(), new WalletTransformer());
    }
    
    public function includeDocument(User $user) : Item
    {
        return $this->item($user->getDocument(), new DocumentTransformer());
    }
}
