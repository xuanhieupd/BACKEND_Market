<?php

namespace App\Modules\Order\Requests;

use App\Base\AbstractRequest;
use App\Base\Requests\VariantsRequestTrait;

class AddRequest extends AbstractRequest
{

    use VariantsRequestTrait;

    /**
     * Rules
     *
     * @return string[]
     * @author xuanhieupd
     */
    public function rules()
    {
        return array_merge(array(
            'customer_id' => 'required|numeric',
            'datas.*.price' => 'required|numeric',
        ), $this->getVariantsRules());
    }


}
