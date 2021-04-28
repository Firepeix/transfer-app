<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as BaseUser;
use Illuminate\Notifications\Notifiable;

class User extends BaseUser
{
    public const STANDARD = 'standard';
    public const STORE_KEEPER = 'store_keeper';
    
    use HasFactory;
    use Notifiable;
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
