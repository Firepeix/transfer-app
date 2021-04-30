<?php


namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractModel extends Model
{
    use HasFactory;
    
    private Carbon $datePrimitive;
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->datePrimitive = app(Carbon::class);
    }
    
    public function getId() : int
    {
        return $this->id;
    }
    
    public function validate() : void
    {
    }
    
    public function getCreatedAt() : Carbon
    {
        return $this->datePrimitive::parse($this->created_at);
    }
    
    public function getUpdatedAt() : Carbon
    {
        return $this->datePrimitive::parse($this->updated_at);
    }
}
