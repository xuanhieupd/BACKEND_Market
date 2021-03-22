<?php

namespace App\Modules\Likeable\Requests;

use App\Base\AbstractRequest;
use App\Modules\Store\Modules\SettingUser\Constants\Constants;

class ResponseUserRequest extends AbstractRequest
{

    /**
     * @return string[]
     */
    public function rules()
    {
        return array(
            'like_ids' => 'required|array',
            'like_ids.*' => 'required|numeric',
            'display_id' => 'required|in:' . implode(',', Constants::getDisplays()),
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