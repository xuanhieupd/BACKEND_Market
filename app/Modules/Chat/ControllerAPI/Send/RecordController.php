<?php

namespace App\Modules\Chat\ControllerAPI\Send;

use App\Libraries\Chat\ConfigurationManager;
use App\Modules\Attachment\Models\Repositories\Contracts\AttachmentInterface;
use App\Modules\Attachment\Models\Repositories\Eloquents\AttachmentRepository;
use App\Modules\Chat\Requests\RecordRequest;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use App\Libraries\Chat\Facades\ChatFacade as Chat;

class RecordController extends AbstractControllerSend
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
     * Gửi tin nhắn ghi âm
     *
     * @param RecordRequest $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(RecordRequest $request)
    {
        $conversationInfo = $request->input('conversation');

        $attachmentInfo = $this->attachmentRepo
            ->select(array('attachment_id'))
            ->where('attachment_id', $request->get('attachment_id'))
            ->first();

        $messageParams = array(
            'message' => $request->get('message'),
            'attachment' => $attachmentInfo,
        );

        $messageInfo = Chat::message($messageParams)
            ->type(ConfigurationManager::CHAT_MESSAGE_TYPE_RECORD)
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
