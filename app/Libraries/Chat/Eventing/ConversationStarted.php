<?php

namespace App\Libraries\Chat\Eventing;

use App\Libraries\Chat\Models\Conversation;

class ConversationStarted extends Event
{
    /**
     * @var Conversation
     */
    public $conversation;

    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }
}
