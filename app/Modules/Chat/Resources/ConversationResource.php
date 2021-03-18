<?php

namespace App\Modules\Chat\Resources;

use App\Base\AbstractResource;

class ConversationResource extends AbstractResource
{

    /**
     * @param $request
     * @return array
     * @author xuanhieupd
     */
    public function toArray($request)
    {
        return array(
            'conversation_id' => $this->getAttribute('conversation_id'),
            'participants' => ConversationParticipantResource::collection($this->participants),
            'message' => new MessageResource($this->lastMessage),
            'created_date' => $this->getCreatedDate(),
            'updated_date' => $this->getUpdatedDate(),
        );
    }

}
