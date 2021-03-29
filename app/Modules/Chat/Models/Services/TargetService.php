<?php

namespace App\Modules\Chat\Models\Services;

use App\Modules\Chat\Constants\BulkConstants;
use App\Modules\Chat\DAO\TargetDAO;
use App\Modules\Store\Modules\SettingUser\Models\Repositories\Contracts\SettingUserInterface;
use App\Modules\Store\Modules\SettingUser\Models\Repositories\Eloquents\SettingUserRepository;
use Illuminate\Support\Collection;

class TargetService
{

    public function getDataFromTarget(TargetDAO $targetInfo, $storeId)
    {
        switch ($targetInfo->getTarget()) {
            case BulkConstants::TARGET_ALL:
                return $this->getAll($storeId);

            case BulkConstants::TARGET_SPECIFIC_GROUP:
                return $this->getAllInGroup($storeId, $targetInfo->getTargetIds());

            case BulkConstants::TARGET_SPECIFIC_USERS:
                return $this->getUsers($storeId, $targetInfo->getTargetIds());
        }

        return collect();
    }

    /**
     * Tất cả người theo dõi cửa hàng
     *
     * @param $storeId
     * @return Collection
     * @author xuanhieupd
     */
    public function getAll($storeId)
    {
        return $this->_getSettingUserModel()->getSettings()
            ->with(array('settingUserUser'))
            ->where('store_id', $storeId)
            ->get();
    }

    /**
     * Tất cả người dùng đã được cửa hàng add vào nhóm khách hàng của cửa hàng đó
     *
     * @param $storeId
     * @param $groupIds
     * @return Collection
     * @author xuanhieupd
     */
    public function getAllInGroup($storeId, array $groupIds)
    {
        return $this->_getSettingUserModel()->getSettings()
            ->with(array('settingUserUser'))
            ->where('store_id', $storeId)
            ->inGroup($groupIds)
            ->get();
    }

    /**
     * Tất cả người dùng được chỉ đích
     *
     * @param $storeId
     * @param $userIds
     * @return Collection
     * @author xuanhieupd
     */
    public function getUsers($storeId, $userIds)
    {
        return $this->_getSettingUserModel()->getSettings()
            ->with(array('settingUserUser'))
            ->where('store_id', $storeId)
            ->whereIn('user_id', $userIds)
            ->get();
    }

    /**
     * @return SettingUserRepository
     */
    protected function _getSettingUserModel()
    {
        return app(SettingUserInterface::class);
    }
}
