<?php

namespace App\Modules\Likeable\Resources;

use App\Base\AbstractResource;

class StoreResource extends AbstractResource
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
            'store_id' => $this->getId(),
            'title' => $this->getAttribute('title'),
            'avatar_url' => $this->getAvatarUrl(),
            'follow_count' => $this->getLikeCountAttribute(),
            'is_following' => true,
            'data' => ProductResource::collection($this->getAttribute('datas')),
        );
    }
}
