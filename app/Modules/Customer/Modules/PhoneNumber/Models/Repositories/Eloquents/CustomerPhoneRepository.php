<?php

/**
 * Customer Phone Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package PhoneNumber
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Customer\Modules\PhoneNumber\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Customer\Modules\Address\Models\Entities\CustomerAddress;
use App\Modules\Customer\Modules\PhoneNumber\Models\Entities\CustomerPhone;
use App\Modules\Customer\Modules\PhoneNumber\Models\Repositories\Contracts\CustomerPhoneInterface;
use Illuminate\Support\Collection;

class CustomerPhoneRepository extends AbstractRepository implements CustomerPhoneInterface
{

    /**
     * Lấy danh sách số điện thoại
     *
     * @param $customerId
     * @param array $conditions
     * @param array $fetchOptions
     * @return Collection|CustomerPhone
     * @author xuanhieupd
     */
    public function getCustomerAddresses($customerId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('customer_id', $customerId)
            ->get();
    }

    /**
     * @return CustomerPhone
     */
    public function model()
    {
        return CustomerPhone::class;
    }

}
