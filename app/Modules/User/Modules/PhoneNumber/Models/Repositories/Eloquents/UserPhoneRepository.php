<?php

/**
 * User Phone Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package PhoneNumber
 * @copyright (c) 10.10.2020, HNW
 */

namespace App\Modules\User\Modules\PhoneNumber\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\User\Modules\PhoneNumber\Models\Entities\UserPhone;
use App\Modules\User\Modules\PhoneNumber\Models\Repositories\Contracts\UserPhoneInterface;

class UserPhoneRepository extends AbstractRepository implements UserPhoneInterface
{

    /**
     * @return UserPhone
     */
    public function model()
    {
        return UserPhone::class;
    }

}
