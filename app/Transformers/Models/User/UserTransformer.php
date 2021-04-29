<?php


namespace App\Transformers\Models\User;


use App\Models\User;
use App\Transformers\AbstractTransformer;

class UserTransformer extends AbstractTransformer
{
    public function transform(User $user) : array
    {
        return $this->change($user, [
            'name' => $user->getFullName(),
            'email' => $user->getEmail()
        ]);
    }
}
