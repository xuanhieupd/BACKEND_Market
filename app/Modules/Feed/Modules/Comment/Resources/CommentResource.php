<?php

namespace App\Modules\Feed\Modules\Comment\Resources;

use App\Base\AbstractResource;

class CommentResource extends AbstractResource
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
            'comment_id' => $this->getId(),
            'message' => $this->getAttribute('message'),
            'author' => new AuthorResource($this->author),
            'created_at' => $this->getCreatedDate(),
        );
    }

}
