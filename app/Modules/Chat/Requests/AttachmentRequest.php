<?php

namespace App\Modules\Chat\Requests;

use App\Base\AbstractRequest;
use Illuminate\Contracts\Validation\Validator;

class AttachmentRequest extends AbstractRequest
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
            'attachment_ids' => 'required|array',
            'attachment_ids.*' => 'required|numeric',
        );
    }

    /**
     * @param Validator $validator
     * @return Validator
     * @author xuanhieupd
     */
    public function afterValidator(Validator $validator)
    {
        $attachmentIds = $this->get('attachment_ids', array());

        return parent::afterValidator($validator);
    }


}
