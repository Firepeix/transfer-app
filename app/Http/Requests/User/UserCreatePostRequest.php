<?php

namespace App\Http\Requests\User;

use App\Http\Requests\AbstractRequest;
use App\Http\Rules\User\DocumentRule;
use App\Models\User;
use App\Primitives\NumberPrimitive;
use Illuminate\Validation\Rule;

class UserCreatePostRequest extends AbstractRequest
{
    public function authorize(): bool
    {
       return true;
    }

    public function rules(): array
    {
        $type = $this->getType();
        return [
            'name' => ['required', 'min:2', 'max:255'],
            'email' => ['required', 'min:2', Rule::unique('users', 'email')],
            'password' => ['required'],
            'document' => ['required', 'min:11', 'max:14', new DocumentRule($type), Rule::unique('documents', 'value')],
            'type' => ['required', Rule::in([User::STANDARD, User::STORE_KEEPER])],
        ];
    }
    
    public function getName() : string
    {
        return $this->get('name');
    }
    
    public function getEmail() : string
    {
        return $this->get('email');
    }
    
    public function createPassword() : string
    {
        return password_hash($this->get('password'), PASSWORD_BCRYPT);
    }
    
    public function getDocument() : string
    {
        return NumberPrimitive::clean($this->get('document'));
    }
    
    public function getType() : string
    {
        return $this->get('type') ?? '';
    }
}
