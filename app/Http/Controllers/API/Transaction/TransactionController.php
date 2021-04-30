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
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TransactionController extends Controller
{
    private TransactionServiceInterface    $service;
    private PaymentServiceInterface        $paymentService;
    private TransactionRepositoryInterface $repository;
    private UserRepositoryInterface        $userRepository;
    private NotificationServiceInterface   $notificationService;
    
    public function __construct(
        Request $request,
        TransactionServiceInterface $service,
        TransactionRepositoryInterface $repository,
        PaymentServiceInterface $paymentService,
        UserRepositoryInterface $userRepository,
        NotificationServiceInterface $notificationService
    ){
        parent::__construct($request);
        $this->service        = $service;
        $this->repository     = $repository;
        $this->paymentService = $paymentService;
        $this->userRepository = $userRepository;
        $this->notificationService = $notificationService;
    }
    
    public function transfer(TransferPostRequest $request): JsonResponse
    {
        $this->databaseController::beginTransaction();
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
        $transformer = new TransactionTransformer();
        $transformer->addIncludes('payerWallet', 'payeeWallet', 'actionUser');
        $this->databaseController::commit();
        return $this->getItemResponse($transaction, $transformer, compact('message'));
    }
}
