<?php


namespace App\Http\Controllers\API\User;


use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserCreatePostRequest;
use App\Http\Requests\User\UserUpdatePutRequest;
use App\Repositories\Interfaces\User\UserRepositoryInterface;
use App\Services\Interfaces\User\UserServiceInterface;
use App\Transformers\Models\User\UserTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private UserServiceInterface    $service;
    private UserRepositoryInterface $repository;
    
    public function __construct(Request $request, UserServiceInterface $service, UserRepositoryInterface $repository)
    {
        parent::__construct($request);
        $this->repository = $repository;
        $this->service    = $service;
    }
    
    public function index() : JsonResponse
    {
        $users = $this->repository->getUsers();
        return $this->getCollectionResponse($users, new UserTransformer());
    }
    
    public function store(UserCreatePostRequest $request): JsonResponse
    {
        DB::beginTransaction();
        $user        = $this->service->createUser($request->getName(), $request->getEmail(), $request->createPassword(), $request->getType());
        $document    = $this->service->createDocument($user, $request->getDocument());
        $wallet      = $this->service->createWallet($user);
        $this->repository->insertUser($user, $document, $wallet);
        $message = 'Cadastro realizado com sucesso';
        $transactionTransformer = new UserTransformer();
        $transactionTransformer->addIncludes('document', 'wallet');
        DB::commit();
        return $this->getItemResponse($user, $transactionTransformer, compact('message'));
    }
    
    public function update(UserUpdatePutRequest $request, int $userId): JsonResponse
    {
        DB::beginTransaction();
        $user = $this->repository->findOrFail($userId);
        $this->service->updateUser($user, $request->getName(), $request->createPassword());
        $this->repository->update($user);
        $message = 'Usuário atualizado com sucesso';
        $transformer = new UserTransformer();
        $transformer->addIncludes('document', 'wallet');
        DB::commit();
        return $this->getItemResponse($user, $transformer, compact('message'));
    }
}
