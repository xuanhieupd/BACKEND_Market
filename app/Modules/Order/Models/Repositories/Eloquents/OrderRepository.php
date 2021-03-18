<?php

/**
 * Order Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Order
 * @copyright (c) 03.10.2020, HNW
 */

namespace App\Modules\Order\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;

use App\Modules\Customer\Exceptions\CustomerNotFoundException;
use App\Modules\Customer\Models\Entities\Customer;
use App\Modules\Customer\Models\Repositories\Contracts\CustomerInterface;
use App\Modules\Order\Models\Repositories\Contracts\OrderInterface;
use App\Modules\Order\Models\Entities\Order;
use App\Modules\User\Models\Entities\User;
use App\Modules\User\Models\Repositories\Contracts\UserInterface;
use App\Modules\User\Models\Repositories\Eloquents\UserRepository;
use App\PermissionConstants;
use Illuminate\Support\Collection;

class OrderRepository extends AbstractRepository implements OrderInterface
{

    /**
     * Danh sách đơn hàng
     *
     * @param array $conditions
     * @param array $fetchOptions
     * @return $this
     * @author xuanhieupd
     */
    public function getOrders(array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions);
    }

    /**
     * Danh sách đơn hàng của người dùng
     *
     * @param $userId
     * @param array $conditions
     * @param array $fetchOptions
     * @return $this
     * @author xuanhieupd
     */
    public function getUserOrders($userId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->getOrders()->where('user_id', $userId);
    }

    /**
     * Lấy danh sách toa hàng
     *
     * @param $storeId
     * @param User $userInfo
     * @param $filterParams
     * @param array $conditions
     * @param array $fetchOptions
     * @return mixed
     * @author xuanhieupd
     */
    public function getStoreOrders($storeId, User $userInfo, $filterParams, array $conditions = array(), array $fetchOptions = array())
    {
        $slaveUsers = $this->_getUserModel()->getStoreSlaveUsersBySupervisor($storeId, $userInfo, array(), array(
            'fields' => array('user_id'),
        ));

        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->filter($filterParams)
            ->viewStatuses($this->getStatusesCanView($userInfo))
            ->filterSlave($userInfo, $slaveUsers->pluck('user_id')->toArray())
            ->get();
    }

    /**
     * Lấy thông tin toa hàng theo Id
     *
     * @param $userId
     * @param $orderId
     * @return Order
     * @author xuanhieupd
     */
    public function getUserOrderById($userId, $orderId)
    {
        return $this->makeModel()
            ->where('user_id', $userId)
            ->where('order_id', $orderId);
    }

    /**
     * Lấy thông tin khách hàng từ dữ liệu Order
     *
     * @param Order $orderInfo
     * @return Customer
     * @throws CustomerNotFoundException
     * @author xuanhieupd
     */
    public function getCustomerByOrder(Order $orderInfo)
    {
        $customerRepo = app(CustomerInterface::class);
        $customerInfo = $customerRepo->getStoreCustomerById($orderInfo->getStoreId(), $orderInfo->getCustomerId());

        if (!$customerInfo) {
            throw new CustomerNotFoundException();
        }

        return $customerInfo;
    }

    /**
     * Danh sách trạng thái toa hàng người dùng có thể xem
     *
     * @param User $user
     * @return Collection
     * @author xuanhieupd
     */
    protected function getStatusesCanView(User $user)
    {
        $statuses = collect();

        if ($user->tokenCan(PermissionConstants::ORDER_VIEW_DONE)) {
            $statuses->push(Order::ORDER_PENDING);
        }

        if ($user->tokenCan(PermissionConstants::ORDER_VIEW_DONE)) {
            $statuses->push(Order::ORDER_PENDING_WAREHOUSE);
        }

        if ($user->tokenCan(PermissionConstants::ORDER_VIEW_DONE)) {
            $statuses->push(Order::ORDER_WAREHOUSE_CONFIRM);
        }

        if ($user->tokenCan(PermissionConstants::ORDER_VIEW_DONE)) {
            $statuses->push(Order::ORDER_DONE);
        }

        return $statuses;
    }

    /**
     * @return Order
     */
    public function model()
    {
        return Order::class;
    }

    /**
     * @return UserRepository
     */
    protected function _getUserModel()
    {
        return app(UserInterface::class);
    }

}
