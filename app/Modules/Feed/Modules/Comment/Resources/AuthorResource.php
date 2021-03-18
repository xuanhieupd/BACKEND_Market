<?php

namespace App\Modules\Feed\Modules\Comment\Resources;

use App\Base\AbstractResource;

class AuthorResource extends AbstractResource
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
            'type' => strtoupper(class_basename($this->resource)),
            'id' => $this->getId(),
            'fullname' => $this->getFullName(),
            'avatar_url' => $this->getAvatarUrl(),
        );
    }

}
