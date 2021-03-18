<?php

namespace App\Modules\Attachment\Resources;

use App\Base\AbstractResource;
use Illuminate\Http\Request;

class AttachmentResource extends AbstractResource
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
            'attachment_id' => $this->getId(),
            'attachment_url' => $this->getLinkUrl(),
            'type' => $this->getType(),
        );
    }
}
