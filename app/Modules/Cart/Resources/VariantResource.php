<?php

namespace App\Modules\Cart\Resources;

use App\Base\AbstractResource;

class VariantResource extends AbstractResource
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
            'variant_id' => $this->getId(),
            'product_id' => $this->getAttribute('product_id'),
            'color_id' => $this->getAttribute('color_id'),
            'size_id' => $this->getAttribute('size_id'),
            'color' => new ColorResource($this->getAttribute('variantColor')),
            'size' => new SizeResource($this->getAttribute('variantSize')),
        );
    }

}
