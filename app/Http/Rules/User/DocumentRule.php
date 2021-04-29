<?php

namespace App\Http\Rules\User;

use App\Models\User;
use App\Models\User\Document;
use App\Primitives\NumberPrimitive;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class DocumentRule implements Rule
{
    private string $type;
    private array $messages;
    
    public function __construct(string $type)
    {
        $this->type = $type;
        $this->messages = [];
    }

    public function passes($attribute, $value) : bool
    {
        if ($value !== null) {
            return $this->validateGeneral($value);
        }
        $this->messages[] = 'Documento não pode ser um valor nulo';
        return false;
    }
    
    private function getDocumentTypeByUser() : string
    {
        return $this->type === User::STANDARD ? Document::CPF : Document::CNPJ;
    }
    
    private function validateGeneral(string $document) : bool
    {
        return $this->getDocumentTypeByUser() === Document::CPF ? $this->validateCPF($document) : $this->validateCNPJ($document);
    }
    
    private function validateCPF(string $documentValue) : bool
    {
        $number = NumberPrimitive::clean($documentValue);
        $length = Str::length(NumberPrimitive::clean($number));
        
        if ($length !== 11) {
            $this->messages[] = 'Devido ao tipo de usuário em documento deve ser informar o CPF';
            $this->messages[] = 'Numero de caracteres invalido para CPF';
            return false;
        }
        
        if (!Document::validateCpf($number)) {
            $this->messages[] = 'Numero de CPF inexistente ou invalido!';
            return false;
        }
        
        return true;
    }
    
    private function validateCNPJ(string $documentValue) : bool
    {
        $number = NumberPrimitive::clean($documentValue);
        $length = Str::length(NumberPrimitive::clean($number));
        
        if ($length !== 14) {
            $this->messages[] = 'Devido ao tipo de usuário em documento deve ser informar o CNPJ';
            $this->messages[] = 'Numero de caracteres invalido para CNPJ';
            return false;
        }
        
        return true;
    }

    public function message() : array
    {
        return $this->messages;
    }
}
