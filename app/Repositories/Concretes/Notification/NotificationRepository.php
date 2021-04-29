<?php


namespace App\Repositories\Concretes\Notification;


use App\Models\AbstractModel;
use App\Models\Notification\Notification;
use App\Repositories\Concretes\AbstractRepository;
use App\Repositories\Interfaces\Transaction\TransactionRepositoryInterface;

class NotificationRepository extends AbstractRepository implements TransactionRepositoryInterface
{
    protected function getModel(): AbstractModel
    {
        return new Notification();
    }
}
