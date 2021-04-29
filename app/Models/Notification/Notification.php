<?php


namespace App\Models\Notification;


use App\Models\AbstractModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends AbstractModel
{
    public function toUser() : BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
    
    public function getToUserId() : int
    {
        return $this->to_user_id;
    }
    
    public function getToUser() : User
    {
        return $this->toUser;
    }
    
    public function setToUser(User $user) : void
    {
        $this->to_user_id = $user->getId();
        $this->setRelation('user', $user->getId());
    }
    
    public function getMessage() : string
    {
        return $this->message;
    }
    
    public function setMessage(string $message) : void
    {
        $this->message = $message;
    }
    
    public function getType() : int
    {
        return $this->type;
    }
    
    public function setType(int $type) : void
    {
        $this->type = $type;
    }
}
