<?php

namespace App\Modules\Customer\Resources;

use App\Base\AbstractResource;
use Illuminate\Http\Request;

class CustomerResource extends AbstractResource
{

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return array(
            'customer_id' => $this->getId(),
            'avatar_url' => $this->getAvatarUrl(),
            'fullname' => $this->getFullName(),
            'phone_number' => $this->getPhoneNumber(),
            'address' => $this->getAddress(),
            'debt' => $this->getDebtAmount(),
            'balance' => $this->getBalance(),
        );
    }

}
