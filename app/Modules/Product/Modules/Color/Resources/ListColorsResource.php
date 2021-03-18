<?php

namespace App\Modules\Product\Modules\Color\Resources;

use App\Base\AbstractResource;
use Illuminate\Http\Request;

class ListColorsResource extends AbstractResource
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
            'color_id' => $this->getId(),
            'hex_color' => $this->getAttribute('hex_color'),
            'title' => $this->getName(),
        );
    }
}
