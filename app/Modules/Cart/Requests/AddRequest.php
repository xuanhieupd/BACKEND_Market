<?php

namespace App\Modules\Cart\Requests;

use App\Base\AbstractRequest;
use App\Base\Requests\VariantsRequestTrait;

class AddRequest extends AbstractRequest
{

    use VariantsRequestTrait;

    /**
     * Rules
     *
     * @return array
     * @author xuanhieupd
     */
    public function rules()
    {
        return $this->getVariantsRules();
    }

}
