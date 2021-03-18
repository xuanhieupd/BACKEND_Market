<?php

namespace App\Modules\Product\Modules\Size\Resources;

use App\Base\AbstractResource;
use Illuminate\Http\Request;

class ListSizesResource extends AbstractResource
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
            'size_id' => $this->getId(),
            'title' => $this->getName(),
        );
    }
}
