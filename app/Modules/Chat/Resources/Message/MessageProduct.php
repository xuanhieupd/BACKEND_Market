<?php

namespace App\Modules\Chat\Resources\Message;

use App\Base\AbstractResource;
use App\Modules\Product\Resources\ProductResource;

class MessageProduct extends AbstractResource
{

    /**
     * @param $request
     * @return array
     * @author xuanhieupd
     */
    public function toArray($request)
    {
        return (new ProductResource($this->resource))->toArray($request);
    }

}
