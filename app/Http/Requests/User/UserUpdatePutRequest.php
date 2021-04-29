<?php

namespace App\Http\Requests\User;

use App\Http\Requests\AbstractRequest;

class UserUpdatePutRequest extends AbstractRequest
{
    public function authorize(): bool
    {
       return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['min:2', 'max:255'],
        ];
    }
    
    public function getName() : ? string
    {
        return $this->get('name');
    }
    
    public function createPassword() : ? string
    {
        $password = $this->get('password');
        return $password !== null ? password_hash($this->get('password'), PASSWORD_BCRYPT) : null;
    }
}
