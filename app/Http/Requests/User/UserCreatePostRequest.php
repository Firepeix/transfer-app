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
        $rulePrimitive = app(Rule::class);
        return [
            'name' => ['required', 'min:2', 'max:255'],
            'email' => ['required', 'min:2', $rulePrimitive::unique('users', 'email')],
            'password' => ['required'],
            'document' => ['required', 'min:11', 'max:14', new DocumentRule($type), $rulePrimitive::unique('documents', 'value')],
            'type' => ['required', $rulePrimitive::in([User::STANDARD, User::STORE_KEEPER])],
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
        $numberPrimitive = app(NumberPrimitive::class);
        return $numberPrimitive::clean($this->get('document'));
    }
    
    public function getType() : string
    {
        return $this->get('type') ?? '';
    }
}
