<?php


namespace App\Repositories\Concretes\Transaction;


use App\Models\AbstractModel;
use App\Models\Transaction\Transaction;
use App\Repositories\Concretes\AbstractRepository;
use App\Repositories\Interfaces\Transaction\TransactionRepositoryInterface;

class TransactionRepository extends AbstractRepository implements TransactionRepositoryInterface
{
    protected function getModel(): AbstractModel
    {
        return new Transaction();
    }
}
