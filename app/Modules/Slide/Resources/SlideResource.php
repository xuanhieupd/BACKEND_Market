<?php

/**
 * Slide Resource
 *
 * @author xuanhieupd
 * @package Slide
 * @copyright (c) 24.08.2020, HNW
 */

namespace App\Modules\Slide\Resources;

use App\Base\AbstractResource;

class SlideResource extends AbstractResource
{

    /**
     * Transform the resource into an array.
     *
     * @param $request
     * @return array
     * @author xuanhieupd
     */
    public function toArray($request)
    {
        return array(
            'slide_id' => $this->getId(),
            'attachment_url' => $this->getImageUrlAttribute(),
            'url' => $this->getDataUrlAttribute(),
        );
    }

}
