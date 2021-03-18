<?php

namespace App\Modules\Product\Resources;

use App\Base\AbstractResource;
use App\Modules\Purchase\Models\Entities\Item;
use Illuminate\Http\Request;

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
            'sku' => $this->getSku(),
            'title' => $this->getAttribute('title'),
            'thumb_url' => $this->getThumbUrl(),
            'price' => $this->getMarketPrice(),
        );
    }


}
