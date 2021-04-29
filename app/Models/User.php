<?php

namespace App\Models;

use App\Models\User\Wallet;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;

class User extends AbstractModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    public const STANDARD = 'standard';
    public const STORE_KEEPER = 'store_keeper';
    
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use MustVerifyEmail;
    use Notifiable;
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }
    
    public function getWallet() : Wallet
    {
        return $this->wallet;
    }
    
    public function getFullName() : string
    {
        return $this->name;
    }
    
    public function isStandard() : bool
    {
        return $this->type === self::STANDARD;
    }
    
    public function getEmail() : string
    {
        return $this->email;
    }
}
