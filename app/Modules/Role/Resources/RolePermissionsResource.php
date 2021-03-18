<?php

namespace App\Modules\Role\Resources;

use App\Base\AbstractResource;
use App\Modules\Permission\Resources\PermissionResource;
use Illuminate\Http\Request;

class RolePermissionsResource extends AbstractResource
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
        return $this->wrapResource(array(
            'role_id' => $this->getId(),
            'title' => $this->getAttribute('title'),
            'data' => PermissionResource::collection($this->rolePermissions)
        ));
    }

}
