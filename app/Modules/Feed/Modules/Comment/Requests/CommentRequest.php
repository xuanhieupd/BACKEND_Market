<?php

namespace App\Modules\Feed\Modules\Comment\Requests;

use App\Base\AbstractRequest;

class CommentRequest extends AbstractRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @author
     */
    public function rules()
    {
        return array(
            'message' => 'required|string',
        );
    }

}
