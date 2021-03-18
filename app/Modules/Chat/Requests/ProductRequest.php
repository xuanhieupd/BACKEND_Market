<?php

namespace App\Modules\Chat\Requests;

use App\Base\AbstractRequest;

class ProductRequest extends AbstractRequest
{

    /**
     * Rules
     *
     * @return string[]
     * @author xuanhieupd
     */
    public function rules()
    {
        return array(
            'product_ids' => 'required|array',
            'product_ids.*' => 'required|numeric',
        );
    }

}
