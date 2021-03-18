<?php

namespace App\Modules\Auth\Resources;

use App\Base\AbstractResource;
use Illuminate\Http\Request;

class ProfileResource extends AbstractResource
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
            'user_id' => $this->getId(),
            'fullname' => $this->getFullName(),
            'avatar_url' => $this->getAvatarUrl(),
        );
    }

}
