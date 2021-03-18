<?php

namespace App\Modules\Chat\Resources\Message;

use App\Base\AbstractResource;
use App\Modules\Attachment\Resources\AttachmentResource;

class MessageAttachment extends AbstractResource
{

    /**
     * @param $request
     * @return array
     * @author xuanhieupd
     */
    public function toArray($request)
    {
        return (new AttachmentResource($this->resource))->toArray($request);
    }

}
