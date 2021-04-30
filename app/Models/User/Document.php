<?php

namespace App\Models\User;

use App\Exceptions\User\InvalidDocumentException;
use App\Exceptions\User\InvalidDocumentTypeException;
use App\Models\AbstractModel;
use App\Models\User;
use App\Primitives\NumberPrimitive;
use Illuminate\Support\Str;

class Document extends AbstractModel
{
    public const CPF = 'cpf';
    public const CNPJ = 'cnpj';
    
    public function register(User $user, string $document) : void
    {
        $this->user_id = $user->exists ? $user->getId() : null;
        $this->value = $document;
        $this->type = $user->isStandard() ? self::CPF : self::CNPJ;
        $this->setRelation('user', $user);
    }
    
    public function getType() : string
    {
        return $this->type;
    }
    
    public function getValue() : string
    {
        return $this->value;
    }
    
    public function commitToUser(User $user) : void
    {
        $this->user_id = $user->getId();
        $this->setRelation('user', $user);
    }
    
    public function validate(): void
    {
        $exception = new InvalidDocumentTypeException($this->type);
        throw_if(!in_array($this->type, [Document::CPF, Document::CNPJ]), $exception);
        $exception = new InvalidDocumentException($this->type);
        if ($this->type === self::CPF) {
            throw_if(!self::validateCpf($this->value), $exception);
        }
        if ($this->type === self::CNPJ) {
            throw_if(!self::validateCNPJ($this->value), $exception);
        }
    }
    
    protected static function validateCNPJ(string $documentValue) : bool
    {
        $number = NumberPrimitive::clean($documentValue);
        $length = Str::length(NumberPrimitive::clean($number));
        
        if ($length !== 14) {
            return false;
        }
        
        return true;
    }
    
    public static function validateCpf(string $cpf): bool
    {
        $cpf      = NumberPrimitive::clean($cpf);
        $splitCpf = str_split($cpf);
        $digits   = [0, 1];
        if (strlen($cpf) < 11) {
            return false;
        }
        
        foreach (range(0, 9) as $digit) {
            if (str_pad('', '11', $digit) === $cpf) {
                return false;
            }
        }
        
        foreach ($digits as $digit) {
            $sum = 0;
            for ($i = 1; $i <= (9 + $digit); $i++) {
                $result = array_slice($splitCpf, $i - 1, $i)[0];
                $sum    += $result * ((11 + $digit) - $i);
            }
            
            $dividend = ($sum * 10) % 11;
            $dividend = $dividend === 10 || $dividend === 11 ? 0 : $dividend;
            $result   = array_slice($splitCpf, 9 + $digit, 10 + $digit);
            $index   = !empty($result) ? (int) $result[0] : 0;
            if ($dividend !== $index) {
                return false;
            }
        }
        
        return true;
    }
    
}
