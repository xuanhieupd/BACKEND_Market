<?php

namespace App\Modules\Chat\Requests;

use App\Base\AbstractRequest;

class TextRequest extends AbstractRequest
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
            'message' => 'required|string',
        );
    }

}
