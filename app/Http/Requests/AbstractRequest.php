<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractRequest extends FormRequest
{
    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->setUp();
    }
    
    protected function setUp() : void
    {
    
    }
}
