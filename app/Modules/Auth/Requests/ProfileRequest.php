<?php

namespace App\Modules\Auth\Requests;

use App\Base\AbstractRequest;
use App\GlobalConstants;
use Illuminate\Validation\Rules\In;

class ProfileRequest extends AbstractRequest
{

    /**
     * @return string[]
     */
    public function rules()
    {
        return array(
            'type_id' => array('required', new In(array(GlobalConstants::DEVICE_IOS, GlobalConstants::DEVICE_ANDROID))),
            'device_id' => 'required|string',
        );
    }

    /**
     * @return string
     */
    public function getDeviceType()
    {
        return $this->get('type_id');
    }

    /**
     * @return string
     */
    public function getDeviceId()
    {
        return $this->get('device_id');
    }

    /**
     * @return string
     */
    public function getPlayerId()
    {
        return $this->get('player_id');
    }

}
