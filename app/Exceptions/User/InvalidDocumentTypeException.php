<?php


namespace App\Exceptions\User;


use Throwable;

class InvalidDocumentTypeException extends UserException
{
    private const CODE = 2;
    
    public function __construct(string $type, Throwable $previous = null)
    {
        $message = "O tipo $type encontrado para o documento é invalido";
        parent::__construct($message, $previous);
    }
    
    public static function getExceptionCode(): int
    {
        return parent::getExceptionCode() . self::CODE;
    }
}
