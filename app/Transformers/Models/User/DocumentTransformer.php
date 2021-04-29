<?php


namespace App\Transformers\Models\User;


use App\Models\User\Document;
use App\Transformers\AbstractTransformer;

class DocumentTransformer extends AbstractTransformer
{
    public function transform(Document $document) : array
    {
        return $this->change($document, [
            'value' => $document->getValue(),
            'type' => $document->getType(),
        ]);
    }
}
