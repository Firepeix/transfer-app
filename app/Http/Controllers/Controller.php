<?php

namespace App\Http\Controllers;

use App\Transformers\AbstractTransformer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\DataArraySerializer;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
    
    private Manager $responseManager;
    
    public function __construct()
    {
        $this->responseManager = new Manager();
        $this->responseManager->setSerializer(new DataArraySerializer());
    }
    
    protected function getItemResponse($item, AbstractTransformer $transformer, array $meta = []): JsonResponse
    {
        $resource = new Item($item, $transformer);
        foreach ($meta as $name => $value) {
            $resource->setMetaValue($name, $value);
        }
        $resource = $this->responseManager->createData($resource)->toArray();
        return new JsonResponse($resource);
    }
}
