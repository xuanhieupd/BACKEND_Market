<?php

/**
 * Customer Address Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package CustomerAddress
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Customer\Modules\Address\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Customer\Modules\Address\Models\Entities\CustomerAddress;
use App\Modules\Customer\Modules\Address\Models\Repositories\Contracts\CustomerAddressInterface;
use Illuminate\Support\Collection;

class CustomerAddressRepository extends AbstractRepository implements CustomerAddressInterface
{

    /**
     * Lấy danh sách địa chỉ
     *
     * @param $customerId
     * @param array $conditions
     * @param array $fetchOptions
     * @return Collection|CustomerAddress
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
     * @return CustomerAddress
     */
    public function model()
    {
        return CustomerAddress::class;
    }

}
