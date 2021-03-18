<?php

namespace App\Modules\Cart\Resources;

use App\Base\AbstractResource;

class SizeResource extends AbstractResource
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
            'size_id' => $this->getId(),
            'title' => $this->getName(),
        );
    }
}
