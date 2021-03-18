<?php

namespace App\Modules\Product\Resources;

use App\Base\AbstractResource;
use App\Modules\Product\Modules\Color\Resources\ListColorsResource;
use App\Modules\Product\Modules\Size\Resources\ListSizesResource;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class VariantResource extends AbstractResource
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
        $variants = $this->resource;
        $colors = $variants->pluck('variantColor')->unique()->values();
        $sizes = $variants->pluck('variantSize')->unique()->values();

        return array(
            'variants' => $this->formatVariants($variants),
            'colors' => ListColorsResource::collection($colors),
            'sizes' => ListSizesResource::collection($sizes),
        );
    }

    /**
     * @param $variants
     * @return Collection
     */
    protected function formatVariants($variants)
    {
        $variantResults = collect();

        foreach ($variants as $variantItem) {
            $variantResults->push(array(
                'variant_id' => $variantItem->getId(),
                'color_id' => $variantItem->getAttribute('color_id'),
                'size_id' => $variantItem->getAttribute('size_id'),
                'current_stock' => $variantItem->getAttribute('current_stock'),
                'total_stock' => $variantItem->getAttribute('total_stock'),
            ));
        }

        return $variantResults;
    }

}
