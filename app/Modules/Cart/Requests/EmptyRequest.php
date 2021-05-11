<?php

namespace App\Modules\Cart\Requests;

use App\Base\AbstractRequest;

class EmptyRequest extends AbstractRequest
{

    /**
     * @return string[]
     */
    public function rules()
    {
        return array(
            'store_ids' => 'required|array',
        );
    }

    /**
     * @return array
     */
    public function getStoreIds()
    {
        return $this->get('store_ids', array());
    }

}
