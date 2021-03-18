<?php

namespace App\Modules\Permission\Resources;

use App\Base\AbstractResource;
use Illuminate\Http\Request;

class PermissionGroupResource extends AbstractResource
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
            'group_id' => $this->getId(),
            'title' => $this->getAttribute('title'),
            'data' => PermissionResource::collection($this->groupPermissions),
        );
    }

}
