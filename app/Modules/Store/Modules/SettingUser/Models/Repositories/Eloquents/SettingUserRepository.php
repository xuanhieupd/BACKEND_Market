<?php

/**
 * Setting User Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package SettingUser
 * @copyright (c) 04.02.2021, HNW
 */

namespace App\Modules\Store\Modules\SettingUser\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Store\Modules\SettingUser\Models\Entities\SettingUser;
use App\Modules\Store\Modules\SettingUser\Models\Repositories\Contracts\SettingUserInterface;

class SettingUserRepository extends AbstractRepository implements SettingUserInterface
{

    public function getSettings()
    {
        return $this->makeModel();
    }

    /**
     * Lấy danh sách cài đặt
     *
     * @param $userId
     * @param $storeIds
     * @param array $conditions
     * @param array $fetchOptions
     * @return $this
     */
    public function getStoreSettingByIds($userId, $storeIds, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('user_id', $userId)
            ->whereIn('store_id', $storeIds);
    }

    /**
     * @return SettingUser
     */
    public function model()
    {
        return SettingUser::class;
    }

}
