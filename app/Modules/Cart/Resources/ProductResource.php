<?php

namespace App\Modules\Cart\Resources;

use App\Base\AbstractResource;

class ProductResource extends AbstractResource
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
            'product_id' => $this->getId(),
            'store_id' => $this->getAttribute('store_id'),
            'sku' => $this->getSku(),
            'title' => $this->getAttribute('title'),
            'thumb_url' => $this->getThumbUrl(),
        );
    }
}
