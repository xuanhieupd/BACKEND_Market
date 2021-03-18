<?php

namespace App\Modules\Auth\Resources;

use App\Base\AbstractResource;
use Illuminate\Http\Request;

class LoginResource extends AbstractResource
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
            'access_token' => $this->getAttribute('api_token'),
        );
    }

}
