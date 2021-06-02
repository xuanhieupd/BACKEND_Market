<?php

namespace App\Modules\Chat\ControllerAPI;

use App\Libraries\Chat\Facades\ChatFacade as Chat;
use App\Modules\Chat\Resources\ConversationResource;
use Illuminate\Http\Request;

class ConversationController extends CreateController
{

    /**
     * @param Request $request
     * @return ConversationResource
     * @throws \Throwable
     */
    public function actionIndex(Request $request)
    {
        $this->request = $request;

        $conversationInfo = Chat::conversations()->between($this->from(), $this->to());
        if (!$conversationInfo) return $this->responseError('Chưa tạo cuộc hội thoại');

        return new ConversationResource($conversationInfo);
    }

}
