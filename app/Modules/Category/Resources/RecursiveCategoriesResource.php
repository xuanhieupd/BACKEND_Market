<?php

namespace App\Modules\Category\Resources;

use App\Base\AbstractResource;
use App\Modules\Base\Helpers\Helper;
use Illuminate\Http\Request;

class RecursiveCategoriesResource extends AbstractResource
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
        return $this->resource->toTree();
    }

}
