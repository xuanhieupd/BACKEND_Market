<?php

namespace App\Modules\Role\Modules\Permission\Resources;

use App\Base\AbstractResource;
use Illuminate\Http\Request;

class PermissionResource extends AbstractResource
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
            'permission_id' => $this->getId(),
            'name_id' => $this->getAttribute('name_id'),
            'title' => $this->getAttribute('title'),
        );
    }
}
