<?php

namespace App\Modules\Customer\Modules\Address\Resources;

use App\Base\AbstractResource;
use Illuminate\Http\Request;

class AddressResource extends AbstractResource
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
            'address_id' => $this->getId(),
            'address' => $this->getAttribute('address'),
        );
    }
}
