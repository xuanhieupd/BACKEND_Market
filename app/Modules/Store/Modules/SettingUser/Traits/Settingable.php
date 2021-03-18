<?php

namespace App\Modules\Store\Modules\SettingUser\Traits;

use App\Modules\Store\Modules\SettingUser\Models\Entities\SettingUser;
use App\Modules\User\Models\Entities\User;

trait Settingable
{

    public function getSetting()
    {

    }

    /**
     * Lưu cài đặt
     *
     * @param User $userInfo
     * @param $nameId
     * @param $value
     * @return SettingUser|false
     * @author xuanhieupd
     */
    public function setSetting(User $userInfo, $nameId, $value)
    {
        $settingInfo = SettingUser::query()
            ->where('store_id', $this->getId())
            ->where('user_id', $userInfo->getId())
            ->first();

        $settingInfo = $settingInfo ? $settingInfo : new SettingUser();

        $settingInfo->fill(array(
            'store_id' => $this->getId(),
            'user_id' => $userInfo->getId(),
            'name_id' => $nameId,
            'value' => $value,
        ));

        return $settingInfo->save() ? $settingInfo : false;
    }

}
