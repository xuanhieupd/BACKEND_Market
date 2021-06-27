<?php

namespace App\Modules\Product\Modules\Seen\Resources;

use App\Base\AbstractResource;
use App\Modules\Product\Resources\ProductResource;

class SeenResource extends AbstractResource
{

    /**
     * Transform the resource into an array.
     *
     * @param $request
     * @return array
     * @author xuanhieupd
     */
    public function toArray($request)
    {
        $productInfo = $this->getAttribute('product');

        $productResource = (new ProductResource($productInfo))->toArray($request);
        $productResource['seen_at'] = $this->getUpdatedDate();

        return $productResource;
    }

}
