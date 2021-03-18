<?php

namespace App\Modules\Store\Resources;

class StoreWithProductCountResource extends StoreResource
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
        $storeResource = parent::toArray($request);
        $storeResource['product_count'] = $this->getAttribute('store_products_count');

        return $storeResource;
    }

}
