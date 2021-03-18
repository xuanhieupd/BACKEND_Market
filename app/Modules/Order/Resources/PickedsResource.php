<?php

namespace App\Modules\Order\Resources;

use App\Base\AbstractResource;
use App\Modules\Product\Modules\Color\Resources\ListColorsResource;
use App\Modules\Product\Modules\Size\Resources\ListSizesResource;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PickedsResource extends AbstractResource
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
        return $this->formatVariants();
    }

    /**
     * @param $variants
     * @return Collection
     */
    protected function formatVariants()
    {
        $variantResults = collect();

        foreach ($this->resource as $pickedInfo) {
            $variantInfo = $pickedInfo->getVariant();

            $variantResults->push(array(
                'variant_id' => $variantInfo->getId(),
                'product_id' => $variantInfo->getAttribute('product_id'),
                'color_id' => $variantInfo->getAttribute('color_id'),
                'size_id' => $variantInfo->getAttribute('size_id'),
                'color' => $variantInfo->variantColor,
                'size' => $variantInfo->variantSize,
                'quantity' => $pickedInfo->getQuantity(),
                'price' => $pickedInfo->getPrice(),
            ));
        }

        return $variantResults;
    }

    /**
     * @return Collection
     */
    public function getVariants()
    {
        $variantResults = collect();

        foreach ($this->resource as $pickedInfo) {
            $variantResults->push($pickedInfo->getVariant());
        }

        return $variantResults;
    }

}
