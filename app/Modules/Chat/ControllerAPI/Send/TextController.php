<?php

namespace App\Modules\Chat\ControllerAPI\Send;

use App\Libraries\Chat\ConfigurationManager;
use App\Modules\Chat\Requests\TextRequest;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use App\Libraries\Chat\Facades\ChatFacade as Chat;

class TextController extends AbstractControllerSend
{

    /**
     * Gửi tin nhắn văn bản
     *
     * @param TextRequest $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(TextRequest $request)
    {
        $conversationInfo = $request->input('conversation');

        $messageParams = array(
            'message' => $request->get('message')
        );

        $messageInfo = Chat::message($messageParams)
            ->type(ConfigurationManager::CHAT_MESSAGE_TYPE_TEXT)
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
