<?php

namespace App\Modules\User\Resources;

use App\Base\AbstractResource;
use Illuminate\Http\Request;

class UserSimpleResource extends AbstractResource
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
        );
    }

}
