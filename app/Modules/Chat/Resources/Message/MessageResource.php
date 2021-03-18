<?php

namespace App\Modules\Chat\Resources\Message;

use App\Base\AbstractResource;
use App\Modules\Chat\Resources\AuthorResource;

class MessageResource extends AbstractResource
{

    /**
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        $attachmentInfo = $this->getAttribute('messageAttachment');

        return array(
            'message_id' => $this->getId(),
            'type' => $this->getType(),
            'text' => $this->getAttribute('body'),
            'created_date' => $this->getCreatedDate(),
            'updated_date' => $this->getUpdatedDate(),
            'attachment' => $attachmentInfo ? new MessageAttachment($this->getAttribute('messageAttachment')) : null,
            'attachments' => MessageAttachment::collection($this->getAttribute('messageAttachments')),
            'products' => MessageProduct::collection($this->getAttribute('messageProducts')),
            'author' => new AuthorResource($this->getSenderAttribute())
        );
    }

}
