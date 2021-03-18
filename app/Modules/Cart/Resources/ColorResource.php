<?php

namespace App\Modules\Cart\Resources;

use App\Base\AbstractResource;

class ColorResource extends AbstractResource
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
            'color_id' => $this->getId(),
            'hex_color' => $this->getHexColor(),
            'title' => $this->getName(),
        );
    }
}
