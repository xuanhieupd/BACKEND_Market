<?php

namespace App\Libraries\Chat;

use App\Base\AbstractModel;
use App\Libraries\Chat\Models\Conversation;
use App\Libraries\Chat\Models\Message;
use App\Libraries\Chat\Models\MessageNotification;
use App\Libraries\Chat\Models\Participation;

abstract class BaseModel extends AbstractModel
{

    /**
     * @param $char
     * @param $strConcat
     * @return string
     */
    protected function getTableConversationWithAppend($char = '', $strConcat = '')
    {
        $tableName = (new Conversation())->getTable();

        return implode($char, array(
            $tableName,
            $strConcat
        ));
    }


    /**
     * @param $char
     * @param $strConcat
     * @return string
     */
    protected function getTableMessageWithAppend($char = '', $strConcat = '')
    {
        $tableName = (new Message())->getTable();

        return implode($char, array(
            $tableName,
            $strConcat
        ));
    }

    /**
     * @param $char
     * @param $strConcat
     * @return string
     */
    protected function getTableParticipationWithAppend($char = '', $strConcat = '')
    {
        $tableName = (new Participation())->getTable();

        return implode($char, array(
            $tableName,
            $strConcat
        ));
    }

    /**
     * @param $char
     * @param $strConcat
     * @return string
     */
    protected function getTableMessageNotificationWithAppend($char = '', $strConcat = '')
    {
        $tableName = (new MessageNotification())->getTable();

        return implode($char, array(
            $tableName,
            $strConcat
        ));
    }

}
