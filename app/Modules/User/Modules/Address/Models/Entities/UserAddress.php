<?php

/**
 * User Address Model
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Address
 * @copyright (c) 10.10.2020, HNW
 */

namespace App\Modules\User\Modules\Address\Models\Entities;

use App\Base\AbstractModel;
use App\Modules\User\Models\Entities\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends AbstractModel
{

    protected $table = 'hnw_user_address';
    protected $primaryKey = 'address_id';
    public static $tableAlias = 'hnw_user_address';

    /**
     * Alias for `address_id`
     *
     * @return int
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function getId()
    {
        return $this->getAttribute('address_id');
    }

    /**
     * Địa chỉ
     *
     * @return string
     * @author xuanhieupd
     */
    public function getAddress()
    {
        return $this->getAttribute('address');
    }

    /**
     * Thông tin khách hàng
     *
     * @return BelongsTo|User
     * @author xuanhieupd
     */
    public function addressUser()
    {
        return $this->belongsTo(User::class, 'customer_id', 'customer_id');
    }

}
