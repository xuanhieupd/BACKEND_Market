<?php

namespace App\Modules\Product\Modules\Color\Resources;

use App\Base\AbstractResource;
use App\Modules\Base\Helpers\Helper;
use Illuminate\Http\Request;

class ColorsResource extends AbstractResource
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
        return Helper::toRecursive($this->resource);
    }

}
