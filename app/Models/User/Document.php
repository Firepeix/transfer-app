<?php

namespace App\Models\User;

use App\Models\AbstractModel;
use App\Models\User;
use App\Primitives\NumberPrimitive;

class Document extends AbstractModel
{
    public const CPF = 'cpf';
    public const CNPJ = 'cnpj';
    
    public function register(User $user, string $document) : void
    {
        $this->user_id = $user->getId();
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
            $index   = !empty($result) ? $result[0] : 0;
            if ($dividend !== $index) {
                return false;
            }
        }
        
        return true;
    }
    
}
