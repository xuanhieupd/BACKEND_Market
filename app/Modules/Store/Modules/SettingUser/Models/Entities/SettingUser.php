<?php

namespace App\Modules\Store\Modules\SettingUser\Models\Entities;

use App\Base\AbstractModelRelation;
use App\Modules\Customer\Models\Entities\Customer;
use App\Modules\User\Models\Entities\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SettingUser extends AbstractModelRelation
{

    protected $table = 'hnw_user_store_setting';
    protected $primaryKey = array('store_id', 'user_id', 'name_id');
    public static $tableAlias = 'hnw_user_store_setting';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'store_id',
        'user_id',
        'display_id',
        'alias_name',
    );

    /**
     * Id
     *
     * @return string
     * @author xuanhieupd
     */
    public function getId()
    {
        return implode('_', array(
            $this->getAttribute('store_id'),
            $this->getAttribute('user_id'),
        ));
    }

    /**
     * DisplayId
     *
     * @return string
     * @author xuanhieupd
     */
    public function getDisplayId()
    {
        return $this->getAttribute('display_id');
    }

    /**
     * Thông tin khách hàng
     *
     * @return BelongsTo
     * @author xuanhieupd
     */
    public function settingUserCustomer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    /**
     * Thông tin người dùng
     *
     * @return BelongsTo
     * @author xuanhieupd
     */
    public function settingUserUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Tìm khách hàng trong nhóm
     *
     * @param Builder $builder
     * @param $groupIds
     * @return Builder
     */
    public function scopeInGroup(Builder $builder, $groupIds)
    {
        return $builder->whereHas('settingUserCustomer', function ($childBuilder) use ($groupIds) {
            return $childBuilder->whereIn('customer_group_id', $groupIds);
        });
    }
}

