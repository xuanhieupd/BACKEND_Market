<?php

namespace App\Modules\Chat\Resources;

use App\Base\AbstractResource;

class ConversationParticipantResource extends AbstractResource
{

    /**
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        $messageable = $this->resource->messageable;

        return array_merge(
            array('participation_id' => $this->getId()),
            (new AuthorResource($messageable))->toArray($request)
        );
    }

}
