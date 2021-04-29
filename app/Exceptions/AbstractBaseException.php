<?php


namespace App\Exceptions;

use Exception;
use Throwable;

abstract class AbstractBaseException extends Exception
{
    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, static::getExceptionCode(), $previous);
    }
    
    abstract public static function getExceptionCode() : int;
    
    public function log() : void
    {
    
    }
}
