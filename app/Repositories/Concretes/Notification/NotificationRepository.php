<?php


namespace App\Repositories\Concretes\Notification;


use App\Models\AbstractModel;
use App\Models\Notification\Notification;
use App\Repositories\Concretes\AbstractRepository;
use App\Repositories\Interfaces\Notification\NotificationRepositoryInterface;

class NotificationRepository extends AbstractRepository implements NotificationRepositoryInterface
{
    protected function getModel(): AbstractModel
    {
        return new Notification();
    }
}
