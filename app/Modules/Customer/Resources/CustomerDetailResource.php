<?php

namespace App\Modules\Customer\Resources;

use App\Base\AbstractResource;
use App\Modules\Customer\Modules\Group\Resources\GroupResource;
use Illuminate\Http\Request;

class CustomerDetailResource extends AbstractResource
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
            'code' => $this->getAttribute('code'),
            'avatar_url' => $this->getAvatarUrl(),
            'fullname' => $this->getFullName(),
            'phone_number' => $this->getPhoneNumber(),
            'address' => $this->getAddress(),
            'debt' => $this->getDebtAmount(),
            'balance' => $this->getBalance(),
            'group' => new GroupResource($this->customerGroup),
        );
    }

}
