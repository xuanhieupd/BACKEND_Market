<?php

namespace App\Libraries\Chat\Models\Extensions;

use App\Base\AbstractModelRelation;

class MessageAttachment extends AbstractModelRelation
{

    protected $table = 'hnw_chat_message_ex_attachment';
    protected $primaryKey = array(
        'message_id',
        'attachment_id',
    );
    public $timestamps = false;

    /**
     * Alias for id
     *
     * @return string
     * @author xuanhieupd
     */
    public function getId()
    {
        return implode('_', array(
            $this->getAttribute('message_id'),
            $this->getAttribute('attachment_id'),
        ));
    }
}
