<?php

/**
 * Feeds Resource
 *
 * @author xuanhieupd
 * @package Feed
 * @copyright (c) 06.10.2020, HNW
 */

namespace App\Modules\Feed\Resources;

use App\Base\AbstractResource;
use App\Modules\Attachment\Resources\AttachmentResource;
use App\Modules\Feed\Modules\Comment\Resources\AuthorResource;
use App\Modules\Feed\Modules\Comment\Resources\StoreAuthorResource;
use App\Modules\Product\Resources\ProductResource;
use App\Modules\Store\Models\Entities\Store;
use Illuminate\Support\Facades\Auth;

class FeedResource extends AbstractResource
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
        $authorInfo = $this->feedAuthor;

        return array(
            'feed_id' => $this->getId(),
            'author' => $authorInfo instanceof Store ? new StoreAuthorResource($authorInfo) : new AuthorResource($authorInfo),
            'title' => $this->getAttribute('title'),
            'description' => $this->getAttribute('description'),
            'like' => new LikeResource($this->feedLike),
            'is_liked' => $this->liked(Auth::user()),
            'like_count' => $this->getLikeCountAttribute(),
            'comment_count' => $this->getAttribute('feed_comments_count'),
            'products' => ProductResource::collection($this->getAttribute('products')),
            'attachments' => AttachmentResource::collection($this->feedAttachments),
            'created_date' => $this->getCreatedDate(),
            'updated_date' => $this->getUpdatedDate(),
        );
    }
}
