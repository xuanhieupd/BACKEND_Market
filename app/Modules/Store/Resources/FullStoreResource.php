<?php

namespace App\Modules\Store\Resources;

use App\Base\AbstractResource;
use Illuminate\Support\Facades\Auth;

class FullStoreResource extends AbstractResource
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
        $storeResource = (new StoreResource($this->resource))->toArray($request);
        $storeResource['address'] = $this->getAttribute('address');

        $storeResource['is_following'] = $this->liked(Auth::user());
        $storeResource['follow_count'] = $this->getLikeCountAttribute();
        $storeResource['product_count'] = $this->getAttribute('store_products_count');

        return $storeResource;
    }

}
