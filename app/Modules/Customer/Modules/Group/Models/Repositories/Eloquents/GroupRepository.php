<?php

/**
 * Customer Group Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Group
 * @copyright (c) 14.11.2020, HNW
 */

namespace App\Modules\Customer\Modules\Group\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Customer\Modules\Group\Models\Entities\Group;
use App\Modules\Customer\Modules\Group\Models\Repositories\Contracts\GroupInterface;
use Illuminate\Support\Collection;

class GroupRepository extends AbstractRepository implements GroupInterface
{

    /**
     * Lấy danh sách nhóm khách hàng
     *
     * @param $storeId
     * @param array $conditions
     * @param array $fetchOptions
     * @return Collection|Group
     * @author xuanhieupd
     */
    public function getStoreCustomerGroups($storeId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('store_id', $storeId)
            ->get();
    }

    /**
     * Lấy thông tin nhóm khách hàng
     *
     * @param $storeId
     * @param $groupId
     * @param array $conditions
     * @param array $fetchOptions
     * @return Group
     * @author xuanhieupd
     */
    public function getStoreGroupById($storeId, $groupId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('store_id', $storeId)
            ->where('group_id', $groupId)
            ->first();
    }

    /**
     * @return Group
     */
    public function model()
    {
        return Group::class;
    }

}
