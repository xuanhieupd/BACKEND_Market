<?php

/**
 * Attachment Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Attachment
 * @copyright (c) 07.10.2020, HNW
 */

namespace App\Modules\Attachment\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Attachment\AbstractHandler;
use App\Modules\Attachment\Handlers\Product;
use App\Modules\Attachment\Models\Entities\Attachment;
use App\Modules\Attachment\Models\Repositories\Contracts\AttachmentInterface;
use Illuminate\Support\Collection;

class AttachmentRepository extends AbstractRepository implements AttachmentInterface
{

    /**
     * @return $this
     */
    public function getAttachments()
    {
        return $this->makeModel();
    }

    /**
     * @param $attachmentId
     * @param array $conditions
     * @param array $fetchOptions
     * @return Attachment
     */
    public function getAttachmentById($attachmentId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('attachment_id', $attachmentId)
            ->first();
    }

    /**
     * Find Attachments by content
     *
     * @param $contentType
     * @param $contentId
     * @param array $conditions
     * @param array $fetchOptions
     * @return Collection|Attachment
     * @author xuanhieupd
     */
    public function findAttachmentsByContent($contentType, $contentId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('content_type', $contentType)
            ->where('content_id', $contentId)
            ->get();
    }

    /**
     * Find Attachment by TempHash
     *
     * @param $tempHash
     * @param array $conditions
     * @param array $fetchOptions
     * @return Collection|Attachment
     * @author xuanhieupd
     */
    public function findAttachmentsByTempHash($tempHash, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('temp_hash', $tempHash)
            ->get();
    }

    /**
     * Class Handler Attachment
     *
     * @param $handlerId
     * @return AbstractHandler
     */
    public function getAttachmentHandler($handlerId)
    {
        $handlers = array(
            'product' => Product::class
        );

        if (!isset($handlers[$handlerId])) {
            return null;
        }

        $handlerClass = $handlers[$handlerId];
        return new $handlerClass($handlerId);
    }

    public function getDefaultAttachmentConstraints()
    {
        return [
            'extensions' => config('attachment.attachmentExtensions'),
            'size' => config('attachment.attachmentMaxFileSize') * 1024,
            'width' => config('attachment.attachmentMaxDimensions.width'),
            'height' => config('attachment.attachmentMaxDimensions.width'),
            'count' => config('attachment.attachmentMaxPerMessage')
        ];
    }

    /**
     * Mapping content với attachments
     *
     * @param $contents
     * @param $contentType
     * @param string $countKey
     * @param string $relationKey
     * @return Collection
     * @author xuanhieupd
     */
    public function addAttachmentsToContents($contents, $contentType, $countKey = 'attach_count', $relationKey = 'entityAttachments')
    {
        $contentIds = collect();
        foreach ($contents as $content) {
            $contentIds->push($content->getId());
        }

        if ($contentIds->isEmpty()) {
            return $contents;
        }

        $attachments = $this->makeModel()
            ->where('content_type', $contentType)
            ->whereIn('content_id', $contentIds->toArray())
            ->orderBy('attach_date')
            ->get();

        foreach ($contents as $content) {
            $contentAttachments = $attachments->where('content_id', $content->getId());

            $content->setAttribute($relationKey, $contentAttachments);
        }

        return $contents;
    }

    /**
     * @param $content
     * @param $contentType
     * @param string $countKey
     * @param string $relationKey
     * @return Collection
     */
    public function addAttachmentsToContent($content, $contentType, $countKey = 'attach_count', $relationKey = 'entityAttachments')
    {
        $attachments = $this->makeModel()
            ->where('content_type', $contentType)
            ->where('content_id', $content->getId())
            ->get();

        $contentAttachments = $attachments->where('content_id', $content->getId());
        $content->setAttribute($relationKey, $contentAttachments);

        return $content;
    }


    /**
     * @return Attachment
     */
    public function model()
    {
        return Attachment::class;
    }

}
