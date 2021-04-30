<?php


namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class AbstractBaseException extends Exception
{
    protected array $information = [];
    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, static::getExceptionCode(), $previous);
    }
    
    abstract public static function getExceptionCode() : int;
    
    public function log() : void
    {
        $information = [
            'message' => $this->message,
            'trace' => $this->getTrace(),
            'line' => $this->getLine(),
            'moreInfo' => $this->information
        ];
        
        app(Log::class)::info(json_encode($information));
    }
}
