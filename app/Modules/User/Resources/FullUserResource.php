<?php

namespace App\Modules\User\Resources;

use App\Base\AbstractResource;

class FullUserResource extends AbstractResource
{

    /**
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        return array(
            'user_id' => $this->getId(),
            'customer_id' => 1,
            'fullname' => $this->getFullName(),
            'avatar_url' => $this->getAvatarUrl(),
            'phone_number' => $this->getPhoneNumber(),
            'created_date' => $this->getCreatedDate(),
        );
    }

}
