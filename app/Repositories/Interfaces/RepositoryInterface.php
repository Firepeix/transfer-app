<?php


namespace App\Repositories\Interfaces;


use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function findOrFail(int $modelId) : Model;
    
    public function insert(AbstractModel $model) : void;
    
    public function update(AbstractModel $model) : void;
}
