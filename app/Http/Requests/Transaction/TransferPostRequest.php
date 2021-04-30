<?php

namespace App\Http\Requests\Transaction;

use App\Http\Requests\AbstractRequest;
use App\Models\User;
use App\Primitives\NumberPrimitive;
use App\Repositories\Interfaces\User\UserRepositoryInterface;
use Illuminate\Validation\Rule;

class TransferPostRequest extends AbstractRequest
{
    private UserRepositoryInterface $userRepository;
    
    protected function setUp(): void
    {
        $this->userRepository = app(UserRepositoryInterface::class);
    }
    
    public function authorize(): bool
    {
       // $policy = new TransactionPolicy();
       // return $policy->transfer($this->user(), $this->getPayer());
       return true;
    }

    public function rules(): array
    {
        $rulePrimitive = app(Rule::class);
        return [
            'payerId' => ['required', 'numeric', $rulePrimitive::exists('users', 'id')],
            'payeeId' => ['required', 'numeric', $rulePrimitive::exists('users', 'id')],
            'value' => ['required', 'numeric', 'min:1']
        ];
    }
    
    public function getPayer() : ? User
    {
        $payerId = $this->get('payerId');
        return $payerId !== null ? $this->userRepository->findOrFail($payerId) : $this->user();
    }
    
    public function getPayee() : User
    {
        return $this->userRepository->findOrFail($this->get('payeeId'));
    }
    
    public function getAmount() : int
    {
        $numberPrimitive = app(NumberPrimitive::class);
        return $numberPrimitive::toInt($this->get('value'));
    }
}
