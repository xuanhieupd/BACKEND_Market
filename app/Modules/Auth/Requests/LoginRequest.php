<?php

namespace App\Modules\Auth\Requests;

use App\Base\AbstractRequest;

class LoginRequest extends AbstractRequest
{

    /**
     * Rules
     *
     * @return array
     * @author xuanhieupd
     */
    public function rules()
    {
        return array(
            'credential' => 'required|string',
            'password' => 'required|string',
        );
    }

    /**
     * Tên đăng nhập
     *
     * @return string
     * @author xuanhieupd
     */
    public function getCredential()
    {
        return $this->get('credential');
    }

    /**
     * Mật khẩu
     *
     * @return string
     * @author xuanhieupd
     */
    public function getPassword()
    {
        return $this->get('password');
    }

}
