<?php


namespace App\Http\Controllers\API\User;


use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserCreatePostRequest;
use App\Http\Requests\User\UserUpdatePutRequest;
use App\Repositories\Interfaces\User\UserRepositoryInterface;
use App\Services\Interfaces\User\UserServiceInterface;
use App\Transformers\Models\User\UserTransformer;
use App\Transformers\Models\User\WalletTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    
    public function getWallet(int $userId) : JsonResponse
    {
        $user = $this->repository->findOrFail($userId);
        return $this->getItemResponse($user->getWallet(), new WalletTransformer());
    }
    
    public function store(UserCreatePostRequest $request): JsonResponse
    {
        $this->databaseController::beginTransaction();
        $user        = $this->service->createUser($request->getName(), $request->getEmail(), $request->createPassword(), $request->getType());
        $document    = $this->service->createDocument($user, $request->getDocument());
        $wallet      = $this->service->createWallet($user);
        $this->repository->insertUser($user, $document, $wallet);
        $message = 'Cadastro realizado com sucesso';
        $userTransformer = new UserTransformer();
        $userTransformer->addIncludes('document', 'wallet');
        $this->databaseController::commit();
        return $this->getItemResponse($user, $userTransformer, compact('message'));
    }
    
    public function update(UserUpdatePutRequest $request, int $userId): JsonResponse
    {
        $this->databaseController::beginTransaction();
        $user = $this->repository->findOrFail($userId);
        $this->service->updateUser($user, $request->getName(), $request->createPassword());
        $this->repository->update($user);
        $message = 'Usuário atualizado com sucesso';
        $transformer = new UserTransformer();
        $transformer->addIncludes('document', 'wallet');
        $this->databaseController::commit();
        return $this->getItemResponse($user, $transformer, compact('message'));
    }
}
