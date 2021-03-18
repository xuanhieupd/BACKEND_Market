<?php

namespace App\Modules\Chat\Resources;

use App\Base\AbstractResource;

class MessageResource extends AbstractResource
{

    /**
     * @param $request
     * @return array
     * @author xuanhieupd
     */
    public function toArray($request)
    {
        return array(
            'message_id' => $this->getId(),
            'type' => $this->getType(),
            'text' => $this->getAttribute('body'),
            'author' => new AuthorResource($this->sender),
            'created_date' => $this->getCreatedDate(),
            'updated_date' => $this->getUpdatedDate(),
        );
    }

}
