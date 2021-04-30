<?php

namespace App\Models;

use App\Exceptions\User\InvalidTypeException;
use App\Models\User\Document;
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
    
    public function document(): HasOne
    {
        return $this->hasOne(Document::class);
    }
    
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }
    
    public function register(string $name, string $email, string $password, string $type) : void
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->type = $type;
    }
    
    public function getDocument() : Document
    {
        return $this->document;
    }
    
    public function getWallet() : Wallet
    {
        return $this->wallet;
    }
    
    public function getFullName() : string
    {
        return $this->name;
    }
    
    public function setFullName(string $name) : void
    {
        $this->name = $name;
    }
    
    public function getPassword() : string
    {
        return $this->password;
    }
    
    public function setPassword(string $password) : void
    {
        $this->password = $password;
    }
    
    public function isStandard() : bool
    {
        return $this->type === self::STANDARD;
    }
    
    public function getEmail() : string
    {
        return $this->email;
    }
    
    public function getType() : string
    {
        return $this->type;
    }
    
    public function validate(): void
    {
        $exception = new InvalidTypeException($this->type);
        throw_if(!in_array($this->type, [User::STANDARD, User::STORE_KEEPER]), $exception);
    }
    
    
}
