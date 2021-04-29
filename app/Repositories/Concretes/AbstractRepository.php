<?php


namespace App\Repositories\Concretes;


use App\Models\AbstractModel;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository implements RepositoryInterface
{
    public function save(AbstractModel $model): void
    {
        $model->validate();
        $model->save();
    }
    
    public function findOrFail(int $modelId) : Model
    {
        $model = $this->getModel()->with($this->getDefaultIncludes());
        return $model->findOrFail($modelId);
    }
    
    protected function getDefaultIncludes(): array
    {
        return [];
    }
    
    public function insert(AbstractModel $model): void
    {
        $this->save($model);
    }
    
    public function update(AbstractModel $model): void
    {
        $this->save($model);
    }
    
    abstract protected function getModel(): AbstractModel;
    
}
