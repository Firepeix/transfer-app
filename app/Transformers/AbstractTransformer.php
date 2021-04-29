<?php


namespace App\Transformers;


use App\Models\AbstractModel;
use Illuminate\Support\Str;
use League\Fractal\TransformerAbstract;

abstract class AbstractTransformer extends TransformerAbstract
{
    public function change($item, array $information) : array
    {
        if ($item instanceof AbstractModel) {
            $startTransform = ['id' => $item->getId()];
            $endTransform   = [
                'createdAt' => $item->getCreatedAt()->toDateTimeString(),
                'updatedAt' => $item->getUpdatedAt()->toDateTimeString()
            ];
            return array_merge($startTransform, $information, $endTransform);
        }
        
        return $information;
    }
    
    public function addIncludes(...$includes) : void
    {
        $scope = $this->getCurrentScope();
        $alreadyIncludes = $scope === null ? collect() : collect($scope->getManager()->getRequestedIncludes());
        foreach ($includes as $item) {
            $foundInclude = $alreadyIncludes->first(function (string $include) use ($item) {
                return Str::contains($include, $item);
            });
            if ($foundInclude === null) {
                $this->defaultIncludes[] = $item;
            }
        }
    }
}
