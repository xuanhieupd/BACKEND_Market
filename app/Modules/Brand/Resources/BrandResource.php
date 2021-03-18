<?php

namespace App\Modules\Brand\Resources;

use App\Base\AbstractResource;
use Illuminate\Http\Request;

class BrandResource extends AbstractResource
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
            'brand_id' => $this->getId(),
            'title' => $this->getAttribute('title'),
        );
    }

}
