<?php


namespace App\Exceptions\User;


use Throwable;

class InvalidDocumentException extends UserException
{
    private const CODE = 3;
    
    public function __construct(string $type, Throwable $previous = null)
    {
        $message = "O documento não possui um valor valido para o tipo $type";
        parent::__construct($message, $previous);
    }
    
    public static function getExceptionCode(): int
    {
        return parent::getExceptionCode() . self::CODE;
    }
}
