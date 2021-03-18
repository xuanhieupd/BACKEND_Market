<?php

namespace App\Modules\Cart\Resources;

use App\Base\AbstractResource;
use App\Modules\Store\Resources\StoreResource;

class RawResource extends AbstractResource
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
            'cart_id' => $this->getAttribute('cart_id'),
            'product_id' => $this->getAttribute('product_id'),
            'variant_id' => $this->getAttribute('variant_id'),
            'quantity' => $this->getAttribute('quantity'),
        );
    }

}
