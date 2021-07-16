<?php

namespace App\Modules\Auth\Requests;

use App\Base\AbstractRequest;
use App\Modules\User\Models\Repositories\Contracts\ProfileInterface;
use App\Modules\User\Models\Repositories\Contracts\UserInterface;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Hash;

class RegisterRequest extends AbstractRequest
{

    /**
     * @return string[]
     */
    public function rules()
    {
        return array(
            'phone_number' => 'required|string',
            'password' => 'required|string',
            'fullname' => 'required|string',
        );
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->get('fullname');
    }

    /**
     * @return string
     */
    public function getHashPassword()
    {
        return Hash::make($this->get('password'));
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->get('phone_number');
    }

    /**
     * @param Validator $validator
     * @return Validator|void
     */
    public function afterValidator(Validator $validator)
    {
        $validator = parent::afterValidator($validator);

        $existsPhone = app(ProfileInterface::class)->makeModel()->phoneNumber($this->getPhoneNumber())->first();
        $existsUsername = app(UserInterface::class)->makeModel()->where('email', $this->getPhoneNumber())->first();

        if ($existsPhone || $existsUsername) return $validator->errors()->add('phone_exists', 'Số điện thoại này đã được sử dụng');

        return $validator;
    }
}
