<?php

namespace App\Modules\Chat\ControllerAPI;

use App\Libraries\Chat\Facades\ChatFacade as Chat;
use App\Modules\Chat\Exceptions\ParticipantException;
use App\Modules\Chat\Resources\ConversationResource;
use Illuminate\Http\Request;

class ConversationController extends CreateController
{

    /**
     * @param Request $request
     * @return ConversationResource|mixed
     * @throws \Throwable
     */
    public function actionIndex(Request $request)
    {
        try {
            $this->request = $request;

            $from = $this->from();
            $to = $this->to();

            $conversationInfo = Chat::conversations()->between($from, $to);
            if (!$conversationInfo) return $this->responseError('Không thuộc cuộc hội thoại nào');

            return new ConversationResource($conversationInfo);
        } catch (ParticipantException $participantException) {
            return $this->responseError('ParticipantException');
        } catch (\Exception $e) {
            return $this->responseError('Có lỗi khi tìm kiếm cuộc hội thoại');
        }
    }

}
