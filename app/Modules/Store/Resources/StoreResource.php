<?php

namespace App\Modules\Store\Resources;

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
            'avatar_url' => $this->getAvatarUrl(),
            'title' => $this->getAttribute('title'),
            'phone_numbers' => $this->getAllPhoneNumbers(),
        );
    }

}
