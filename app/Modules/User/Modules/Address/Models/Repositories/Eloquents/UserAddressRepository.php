<?php

/**
 * User Address Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Address
 * @copyright (c) 10.10.2020, HNW
 */

namespace App\Modules\User\Modules\Address\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\User\Modules\Address\Models\Entities\UserAddress;
use App\Modules\User\Modules\Address\Models\Repositories\Contracts\UserAddressInterface;

class UserAddressRepository extends AbstractRepository implements UserAddressInterface
{

    /**
     * @return UserAddress
     */
    public function model()
    {
        return UserAddress::class;
    }

}
