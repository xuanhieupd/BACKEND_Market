<?php

namespace App\Modules\Attachment;

use App\Modules\Attachment\Exceptions\InvalidUploadException;
use App\Modules\Attachment\Exceptions\MaxUploadCountException;
use App\Modules\Attachment\Models\Entities\Attachment;
use App\Modules\Attachment\Models\Repositories\Eloquents\AttachmentRepository;
use App\Modules\Attachment\Models\Services\PreparerService;
use Illuminate\Support\Facades\Auth;

class Manipulator
{

    /**
     * @var AbstractHandler
     */
    protected $handler;

    /**
     * @var AttachmentRepository
     */
    protected $attachmentRepo;
    protected $context;
    protected $hash;
    protected $constraints = [];
    protected $container;

    /**
     * @var Attachment[]
     */
    protected $existingAttachments = [];

    /**
     * @var Attachment[]
     */
    protected $newAttachments = [];

    public function __construct(AbstractHandler $handler, $repo, array $context, $hash)
    {
        $this->handler = $handler;
        $this->attachmentRepo = $repo;

        $this->setContext($context);
        $this->setHash($hash);
        $this->setConstraints($handler->getConstraints($context));
    }

    public function getContext()
    {
        return $this->context;
    }

    public function setContext(array $context)
    {
        $this->context = $context;

        $this->container = $this->handler->getContainerFromContext($context);

        if ($this->container) {
            $existing = $this->attachmentRepo->findAttachmentsByContent($this->handler->getContentType(), $this->handler->getContainerIdFromContext($context));
            $this->existingAttachments = $existing;
        }
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function setHash($hash)
    {
        if (!$hash) {
            throw new \InvalidArgumentException("Hash must be specified");
        }

        $this->hash = $hash;

        $attachments = $this->attachmentRepo->findAttachmentsByTempHash($hash);
        $this->newAttachments = $attachments;
    }

    public function getConstraints()
    {
        return $this->constraints;
    }

    public function setConstraints(array $constraints)
    {
        $this->constraints = $constraints;
    }


    /**
     * Can Upload ?
     *
     * @param null $error
     * @return bool
     * @throws MaxUploadCountException
     */
    public function canUpload()
    {
        $constraints = $this->constraints;
        if (!isset($constraints['count']) || $constraints['count'] > 0) {
            return true;
        }

        $uploaded = count($this->existingAttachments) + count($this->newAttachments);
        $allowed = ($uploaded < $constraints['count']);

        if (!$allowed) {
            throw new MaxUploadCountException(trans('you_may_only_attach_x_files', array('count' => $constraints['count'])));
        }

        return true;
    }

    public function getExistingAttachments()
    {
        return $this->existingAttachments;
    }

    public function getNewAttachments()
    {
        return $this->newAttachments;
    }

    public function deleteAttachment($id)
    {
        if (isset($this->existingAttachments[$id])) {
            $this->existingAttachments[$id]->delete();
            unset($this->existingAttachments[$id]);
            return true;
        }

        if (isset($this->newAttachments[$id])) {
            $this->newAttachments[$id]->delete();
            unset($this->newAttachments[$id]);
            return true;
        }

        return false;
    }

    /**
     * Thêm mới Attachment từ dữ liệu FileUpload
     *
     * @param FileUpload $upload
     * @return Attachment|null
     * @throws InvalidUploadException
     * @author xuanhieupd
     */
    public function insertAttachmentFromUpload(FileUpload $upload)
    {
        $upload->applyConstraints($this->constraints);
        $this->handler->validateAttachmentUpload($upload, $this);

        $errors = $upload->isValid();
        if ($errors->isNotEmpty()) {
            throw new InvalidUploadException($errors->first());
        }

        return (new PreparerService())->insertAttachment(
            $this->handler,
            $upload->getFileWrapper(),
            Auth::user(),
            $this->hash
        );
    }

}
