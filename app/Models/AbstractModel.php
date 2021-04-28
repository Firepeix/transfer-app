<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractModel extends Model
{
    use HasFactory;
    
    public function getId() : int
    {
        return $this->id;
    }
    
    public function validate() : void
    {
    }
}
