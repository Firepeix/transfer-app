<?php


namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

abstract class ModelFactory extends Factory
{
    private array $relations = [];
    
    protected function relation(string $name, Factory $factory) : FactoryRelation
    {
        return new FactoryRelation($name, $factory);
    }
    
    protected function makeInstance(?Model $parent): Model
    {
        $model = parent::makeInstance($parent);
        $this->setRelations($model);
        return $model;
    }
    
    private function setRelations(Model $model) : void
    {
        foreach ($this->relations as $name => $relation) {
            $model->setRelation($name, $relation);
        }
    }
    
    protected function expandAttributes(array $definition): array
    {
        $attributes = collect($definition)->map(function ($attribute, $key) use (&$definition) {
            if (is_callable($attribute) && ! is_string($attribute) && ! is_array($attribute)) {
                $attribute = $attribute($definition);
            }
            if ($attribute instanceof self) {
                $attribute = $attribute->make()->getKey();
            } elseif ($attribute instanceof Model) {
                $attribute = $attribute->getKey();
            } elseif ($attribute instanceof FactoryRelation) {
                $this->relations[$attribute->getName()] = $attribute->getFactory()->make();
                $attribute = $this->relations[$attribute->getName()]->getKey();
            }
            
            
            $definition[$key] = $attribute;
            
            return $attribute;
        })->all();
        
        if (!isset($attributes['id'])) {
            $attributes['id'] = $this->faker->numberBetween(1, 55555);
        }
        
        return $attributes;
    }
    
    protected function addRelation(string $relation, Model $model) : void
    {
        $this->relations[$relation] = $model;
    }
}
