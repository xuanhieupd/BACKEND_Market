<?php

/**
 * Customer Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Customer
 * @copyright (c) 03.10.2020, HNW
 */

namespace App\Modules\Customer\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;

use App\Modules\Customer\Exceptions\CustomerNotFoundException;
use App\Modules\Customer\Models\Repositories\Contracts\CustomerInterface;
use App\Modules\Customer\Models\Entities\Customer;
use Illuminate\Support\Collection;

class CustomerRepository extends AbstractRepository implements CustomerInterface
{

    /**
     * Lấy danh sách khách hàng của cửa hàng
     *
     * @param int $storeId
     * @return Collection|Customer
     * @author xuanhieupd
     */
    public function getStoreCustomers(int $storeId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('store_id', $storeId)
            ->simplePaginate(10);
    }

    /**
     * Lấy thông tin khách hàng theo Id
     *
     * @param $storeId
     * @param $customerId
     * @return Customer|null
     * @throws CustomerNotFoundException
     * @author xuanhieupd
     */
    public function getStoreCustomerById($storeId, $customerId, array $conditions = array(), array $fetchOptions = array())
    {
        $customerInfo = $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('store_id', $storeId)
            ->where('customer_id', $customerId)
            ->first();

        if (!$customerInfo) {
            throw new CustomerNotFoundException();
        }

        return $customerInfo;
    }

    /**
     * Kiểm tra sự tồn tại
     *
     * @param $storeId
     * @param $customerId
     * @return bool
     * @author xuanhieupd
     */
    public function checkExistsStoreCustomerById($storeId, $customerId)
    {
        return $this->makeModel()
            ->where('store_id', $storeId)
            ->where('customer_id', $customerId)
            ->exists();
    }

    /**
     * @return Customer
     */
    public function model()
    {
        return Customer::class;
    }

}
