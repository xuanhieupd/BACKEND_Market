<?php

namespace App\Modules\Chat\ControllerAPI\Send;

use App\Libraries\Chat\ConfigurationManager;
use App\Modules\Attachment\Models\Repositories\Contracts\AttachmentInterface;
use App\Modules\Attachment\Models\Repositories\Eloquents\AttachmentRepository;
use App\Modules\Chat\Requests\AttachmentRequest;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use App\Libraries\Chat\Facades\ChatFacade as Chat;

class AttachmentController extends AbstractControllerSend
{

    /**
     * @var AttachmentRepository
     */
    protected $attachmentRepo;

    /**
     * Constructor.
     *
     * @param AttachmentInterface $attachmentRepo
     * @author xuanhieupd
     */
    public function __construct(AttachmentInterface $attachmentRepo)
    {
        $this->attachmentRepo = $attachmentRepo;
    }

    /**
     * Gửi tin nhắn hình ảnh / video
     *
     * @param AttachmentRequest $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(AttachmentRequest $request)
    {
        $conversationInfo = $request->input('conversation');

        $attachmentIds = $request->get('attachment_ids', array());
        $attachments = $this->attachmentRepo->getAttachments()->whereIn('attachment_id', $attachmentIds)->get();

        $messageParams = array(
            'message' => $request->get('message'),
            'attachments' => $attachments,
        );

        $messageInfo = Chat::message($messageParams)
            ->type(ConfigurationManager::CHAT_MESSAGE_TYPE_ATTACHMENT)
            ->from($this->getAuthor())
            ->to($conversationInfo)
            ->send();

        return $this->responseMessage('Thành công', $this->loadResponse($messageInfo));
    }

    /**
     * @return Authenticatable
     */
    protected function getAuthor()
    {
        return Auth::user();
    }

}
