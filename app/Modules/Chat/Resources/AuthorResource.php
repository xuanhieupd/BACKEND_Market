<?php

namespace App\Modules\Chat\Resources;

use App\Base\AbstractResource;

class AuthorResource extends AbstractResource
{

    /**
     * @param $request
     * @return array
     * @author xuanhieupd
     */
    public function toArray($request)
    {
        return array(
            'id' => $this->getId(),
            'type' => $this->getType(),
            'fullname' => $this->getFullName(),
            'avatar_url' => $this->getAvatarUrl(),
        );
    }

}
