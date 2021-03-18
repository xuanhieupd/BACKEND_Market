<?php

namespace App\Libraries\Chat;

class ConfigurationManager
{
    const CONVERSATIONS_TABLE = 'hnw_chat_conversation';
    const MESSAGES_TABLE = 'hnw_chat_message';
    const MESSAGE_NOTIFICATIONS_TABLE = 'hnw_chat_message_notification';
    const PARTICIPATION_TABLE = 'hnw_chat_participation';

    const CHAT_MESSAGE_TYPE_TEXT = 'text';
    const CHAT_MESSAGE_TYPE_RECORD = 'record';
    const CHAT_MESSAGE_TYPE_PRODUCT = 'product';
    const CHAT_MESSAGE_TYPE_ATTACHMENT = 'attachment';

    public static function paginationDefaultParameters()
    {
        $pagination = config('musonza_chat.pagination', []);

        return [
            'page' => $pagination['page'] ?? 1,
            'perPage' => $pagination['perPage'] ?? 25,
            'sorting' => $pagination['sorting'] ?? 'asc',
            'columns' => $pagination['columns'] ?? ['*'],
            'pageName' => $pagination['pageName'] ?? 'page',
        ];
    }
}
