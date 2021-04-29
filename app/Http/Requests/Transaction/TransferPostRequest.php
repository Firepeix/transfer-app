<?php

namespace App\Http\Requests\Transaction;

use App\Http\Requests\AbstractRequest;
use App\Models\User;
use App\Policies\Transaction\TransactionPolicy;
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
        $policy = new TransactionPolicy();
        return $policy->transfer($this->user(), $this->getPayer());
    }

    public function rules(): array
    {
        return [
            'payerId' => ['required', 'numeric', Rule::exists('users', 'id')],
            'payeeId' => ['required', 'numeric', Rule::exists('users', 'id')],
            'value' => ['required', 'numeric']
        ];
    }
    
    public function getPayer() : ? User
    {
        $id = $this->get('payerId');
        return $id !== null ? $this->userRepository->findOrFail($id) : $this->user();
    }
    
    public function getPayee() : User
    {
        return $this->userRepository->findOrFail($this->get('payeeId'));
    }
    
    public function getAmount() : int
    {
        return NumberPrimitive::toInt($this->get('value'));
    }
}
