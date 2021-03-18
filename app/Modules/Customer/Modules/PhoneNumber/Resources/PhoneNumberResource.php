<?php

namespace App\Modules\Customer\Modules\PhoneNumber\Resources;

use App\Base\AbstractResource;
use Illuminate\Http\Request;

class PhoneNumberResource extends AbstractResource
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
            'phone_id' => $this->getId(),
            'phone_number' => $this->getPhoneNumber(),
        );
    }
}
