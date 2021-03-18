<?php

namespace App\Libraries\Chat\Models\Extensions;

use App\Base\AbstractModelRelation;

class MessageProduct extends AbstractModelRelation
{

    protected $table = 'hnw_chat_message_ex_product';
    protected $primaryKey = array(
        'message_id',
        'product_id',
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
            $this->getAttribute('product_id'),
        ));
    }
}
