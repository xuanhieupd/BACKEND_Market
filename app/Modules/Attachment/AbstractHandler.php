<?php

namespace App\Modules\Attachment;

use App\Modules\Attachment\Models\Entities\Attachment;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractHandler
{
    protected $contentType;

    public function __construct($contentType)
    {
        $this->contentType = $contentType;
    }

    abstract public function canView(Attachment $attachment, Model $container, &$error = null);

    abstract public function canManageAttachments(array $context);

    abstract public function onAttachmentDelete(Attachment $attachment, Model $container = null);

    abstract public function getConstraints(array $context);

    abstract public function getContainerIdFromContext(array $context);

    abstract public function getContainerLink(Model $container, array $extraParams = []);

    abstract public function getContext(Model $entity = null, array $extraContext = []);

    public function validateAttachmentUpload(FileUpload $upload, Manipulator $manipulator)
    {

    }

    public function beforeNewAttachment(FileWrapper $file, array &$extra = [])
    {

    }

    public function onNewAttachment(Attachment $attachment, FileWrapper $file)
    {

    }

    public function prepareAttachmentJson(Attachment $attachment, array $context, array $json)
    {
        return $json;
    }

    public function onAssociation(Attachment $attachment, Model $container = null)
    {

    }

    public function beforeAttachmentDelete(Attachment $attachment, Model $container = null)
    {

    }

    public function getContainerFromContext(array $context)
    {
        $id = $this->getContainerIdFromContext($context);
        return $id ? $this->getContainerEntity($id) : null;
    }

    public function getContainerEntity($id)
    {

    }

    public function getContainerWith()
    {
        return [];
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    public function getContentTypePhrase()
    {
        return \XF::app()->getContentTypePhrase($this->contentType);
    }
}
