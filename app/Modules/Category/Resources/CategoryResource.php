<?php

namespace App\Modules\Category\Resources;

use App\Base\AbstractResource;

class CategoryResource extends AbstractResource
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
            'category_id' => $this->getId(),
            'title' => $this->getAttribute('title'),
            'parent_id' => $this->getAttribute('parent_id'),
        );
    }

}
