<?php

namespace App\Modules\Chat\Requests;

use App\Base\AbstractRequest;

class RecordRequest extends AbstractRequest
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
            'attachment_id' => 'required|numeric',
        );
    }

}
