<?php

namespace App\Modules\Order\Resources;

use App\Base\AbstractResource;
use Illuminate\Http\Request;

class ProductResource extends AbstractResource
{

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     * @author xuanhieupd
     */
    public function toArray($request)
    {
        return array(
            'product_id' => $this->getId(),
            'sku' => $this->getAttribute('sku'),
            'title' => $this->getAttribute('title'),
            'image_url' => $this->getThumbUrl(),
        );
    }

}
