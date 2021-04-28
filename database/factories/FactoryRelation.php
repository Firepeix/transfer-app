<?php


namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;

class FactoryRelation
{
    private string $name;
    private Factory $factory;
    
    public function __construct(string $name, Factory $factory)
    {
        $this->name    = $name;
        $this->factory = $factory;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getFactory(): Factory
    {
        return $this->factory;
    }
}
