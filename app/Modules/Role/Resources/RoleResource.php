<?php

namespace App\Modules\Role\Resources;

use App\Base\AbstractResource;
use Illuminate\Http\Request;

class RoleResource extends AbstractResource
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
            'role_id' => $this->getId(),
            'title' => $this->getAttribute('title'),
        );
    }

}
