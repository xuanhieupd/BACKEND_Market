<?php

/**
 * User Phone Model
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package PhoneNumber
 * @copyright (c) 10.10.2020, HNW
 */

namespace App\Modules\User\Modules\PhoneNumber\Models\Entities;

use App\Base\AbstractModel;
use App\Modules\Customer\Models\Entities\Customer;
use App\Modules\User\Models\Entities\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPhone extends AbstractModel
{

    protected $table = 'hnw_user_phone';
    protected $primaryKey = 'phone_id';
    public static $tableAlias = 'hnw_user_phone';

    /**
     * Alias for `phone_id`
     *
     * @return int
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function getId()
    {
        return $this->getAttribute('phone_id');
    }

    /**
     * Số điện thoại
     *
     * @return string
     * @author xuanhieupd
     */
    public function getPhoneNumber()
    {
        return $this->getAttribute('phone_number');
    }

    /**
     * Thông tin người dùng
     *
     * @return BelongsTo|Customer
     * @author xuanhieupd
     */
    public function phoneUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

}
