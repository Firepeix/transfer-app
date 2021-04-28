<?php

namespace Database\Factories\User;


use App\Models\User\Document;
use Database\Factories\ModelFactory;

class DocumentFactory extends ModelFactory
{
    protected $model = Document::class;
    
    public function definition() : array
    {
        return [
            'value' => $this->faker->cpf(false),
            'type' => Document::CPF
        ];
    }
    
    public function cnpj() : self
    {
        return $this->state(function () {
            return [
                'value' => $this->faker->cnpj(false),
                'type' => Document::CNPJ
            ];
        });
    }
}
