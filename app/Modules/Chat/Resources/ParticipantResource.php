<?php

namespace App\Modules\Chat\Resources;

use App\Base\AbstractResource;

class ParticipantResource extends AbstractResource
{

    /**
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        $conversationInfo = $this->conversation;

        return (new ConversationResource($conversationInfo))->toArray($request);
    }

}
