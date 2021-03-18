<?php

namespace App\Base;


use Illuminate\Http\Resources\Json\JsonResource;

class AbstractResource extends JsonResource
{
    public static $wrap = 'data';

    /**
     * @param $data
     * @return array
     */
    public function wrapResource($data)
    {
        return array(
            'data' => $data
        );
    }
}
