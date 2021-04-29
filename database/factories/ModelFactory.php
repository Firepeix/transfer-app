<?php


namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class ModelFactory extends Factory
{
    private array $relations = [];
    private array $foreignKeys = [];
    
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
            if (isset($this->foreignKeys[$name])) {
                $relation->{$this->foreignKeys[$name]} = $model->getId();
            }
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
    
    protected function addRelation(string $relation, Model $model, $foreignKey = '') : void
    {
        $this->relations[$relation] = $model;
        if ($foreignKey !== '') {
            $this->foreignKeys[$relation] = $foreignKey;
        }
    }
    
    protected function callAfterCreating(Collection $instances, ?Model $parent = null)
    {
        $instances->each(function ($model) use ($parent) {
            $this->afterCreating->each(function ($callback) use ($model, $parent) {
                $callback($model, $parent);
            });
            $this->createRelations($model);
        });
    }
    
    private function createRelations(Model $model) : void
    {
        $relations = $model->getRelations();
        foreach ($relations as $relation) {
            if ($relation instanceof Model) {
                $relation->save();
            }
            if ($relation instanceof Collection) {
                $relation->each(function (Model $model) {
                    $model->save();
                });
            }
        }
    }
}
