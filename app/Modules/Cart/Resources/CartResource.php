<?php

namespace App\Modules\Cart\Resources;

use App\Base\AbstractResource;
use App\Modules\Store\Resources\StoreResource;

class CartResource extends AbstractResource
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
        return array(
            'raw' => RawResource::collection($this->resource['raw']),
            'stores' => StoreResource::collection($this->resource['stores']),
            'products' => ProductResource::collection($this->resource['products']),
            'variants' => VariantResource::collection($this->resource['variants']),
        );
    }

}
