<?php

namespace App\Modules\Activitylog\Resources;

use App\Base\AbstractResource;
use App\Modules\User\Resources\UserSimpleResource;
use Illuminate\Http\Request;

class ActivityResource extends AbstractResource
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
            'activity_id' => $this->getId(),
            'ref' => $this->subject->toRefResource(),
            'name' => $this->getAttribute('log_name'),
            'type' => $this->getAttribute('description'),
            'message' => $this->getMessage(),
            'causer' => new UserSimpleResource($this->causer),
            'created_date' => $this->getCreatedDate(),
            'updated_date' => $this->getUpdatedDate(),
        );
    }

}
