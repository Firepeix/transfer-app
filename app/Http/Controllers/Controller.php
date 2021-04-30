<?php

namespace App\Http\Controllers;

use App\Transformers\AbstractTransformer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection as CollectionResource;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\DataArraySerializer;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
    
    private Manager $responseManager;
    private Request $request;
    
    public function __construct(Request $request)
    {
        $this->responseManager = new Manager();
        $this->request         = $request;
        $this->responseManager->setSerializer(new DataArraySerializer());
        $this->responseManager->parseIncludes($request->get('include') ?? []);
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
    
    protected function getCollectionResponse(Collection $collection, AbstractTransformer $transformer, array $meta = []): JsonResponse
    {
        $resource = new CollectionResource($collection, $transformer);
        foreach ($meta as $name => $value) {
            $resource->setMetaValue($name, $value);
        }
        $resource = $this->responseManager->createData($resource)->toArray();
        return new JsonResponse($resource);
    }
}
