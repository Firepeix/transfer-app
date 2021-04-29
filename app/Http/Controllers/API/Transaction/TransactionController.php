<?php


namespace App\Http\Controllers\API\Transaction;


use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\TransferPostRequest;
use App\Repositories\Interfaces\Transaction\TransactionRepositoryInterface;
use App\Repositories\Interfaces\User\UserRepositoryInterface;
use App\Services\Interfaces\Notification\NotificationServiceInterface;
use App\Services\Interfaces\Payment\PaymentServiceInterface;
use App\Services\Interfaces\Transaction\TransactionServiceInterface;
use App\Transformers\Models\Transaction\TransactionTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    private TransactionServiceInterface    $service;
    private PaymentServiceInterface        $paymentService;
    private TransactionRepositoryInterface $repository;
    private UserRepositoryInterface        $userRepository;
    private NotificationServiceInterface   $notificationService;
    
    public function __construct(
        TransactionServiceInterface $service,
        TransactionRepositoryInterface $repository,
        PaymentServiceInterface $paymentService,
        UserRepositoryInterface $userRepository,
        NotificationServiceInterface $notificationService
    ){
        parent::__construct();
        $this->service        = $service;
        $this->repository     = $repository;
        $this->paymentService = $paymentService;
        $this->userRepository = $userRepository;
        $this->notificationService = $notificationService;
    }
    
    public function transfer(TransferPostRequest $request): JsonResponse
    {
        DB::beginTransaction();
        $payer       = $request->getPayer();
        $payee       = $request->getPayee();
        $transaction = $this->service->createTransaction($payer, $payee, $request->getAmount());
        $this->service->commitTransaction($transaction);
        $this->paymentService->isAuthorized($transaction);
        $this->repository->insert($transaction);
        $this->userRepository->updateWallets(new Collection([$payer->getWallet(), $payee->getWallet()]));
        $notification = $this->service->createNewTransactionNotification($transaction);
        $this->notificationService->dispatchNotification($notification);
        $message = 'Transferência feita com sucesso!';
        $transactionTransformer = new TransactionTransformer();
        $transactionTransformer->addIncludes('payerWallet', 'payeeWallet', 'actionUser');
        DB::commit();
        return $this->getItemResponse($transaction, $transactionTransformer, compact('message'));
    }
}