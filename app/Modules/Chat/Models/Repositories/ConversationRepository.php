<?php

namespace App\Modules\Chat\Models\Repositories;

use App\Base\AbstractRepository;
use App\Libraries\Chat\Facades\ChatFacade as Chat;
use App\Libraries\Chat\Models\Conversation;
use App\Modules\Chat\Models\Repositories\Contracts\ConversationInterface;

class ConversationRepository extends AbstractRepository implements ConversationInterface
{

    /**
     * @param $from
     * @param $to
     * @return Conversation
     */
    public function betweenOrMakeConversation($from, $to)
    {
        $conversationInfo = Chat::conversations()->between($from, $to);
        $conversationInfo = $conversationInfo ?? Chat::createConversation(array($from, $to))->makeDirect();

        return $conversationInfo;
    }

    /**
     * @return string
     */
    public function model()
    {
        return Conversation::class;
    }
}
