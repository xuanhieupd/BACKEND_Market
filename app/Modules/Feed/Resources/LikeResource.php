<?php

namespace App\Modules\Feed\Resources;

use App\Base\AbstractResource;

class LikeResource extends AbstractResource
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
        $userInfo = $this->likeUser;

        return array(
            'user_id' => $userInfo->getId(),
            'fullname' => $userInfo->getFullName(),
            'avatar_url' => $userInfo->getAvatarUrl()
        );
    }

}
