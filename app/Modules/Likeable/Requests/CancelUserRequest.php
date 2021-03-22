<?php

namespace App\Modules\Likeable\Requests;

use App\Base\AbstractRequest;

class CancelUserRequest extends AbstractRequest
{

    /**
     * @return array
     * @author xuanhieupd
     */
    public function rules()
    {
        return array(
            'like_ids' => 'required|array',
            'like_ids.*' => 'required|numeric',
        );
    }

    /**
     * LikeIds
     * @return array
     */
    public function getLikeIds()
    {
        return $this->get('like_ids', array());
    }

    /**
     * @return string
     * @author xuanhieupd
     */
    public function getDisplayId()
    {
        return $this->get('display_id');
    }

}