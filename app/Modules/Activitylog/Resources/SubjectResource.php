<?php

namespace App\Modules\Activitylog\Resources;

use App\Base\AbstractResource;
use Illuminate\Http\Request;

class SubjectResource extends AbstractResource
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
            'id' => 1,
        );
    }

}
