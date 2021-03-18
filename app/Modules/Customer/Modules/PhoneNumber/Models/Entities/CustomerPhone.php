<?php

/**
 * Customer Phone Model
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package PhoneNumber
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Customer\Modules\PhoneNumber\Models\Entities;

use App\Base\AbstractModel;
use App\Modules\Customer\Models\Entities\Customer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerPhone extends AbstractModel
{

    protected $table = 'hnw_customer_phone';
    protected $primaryKey = 'phone_id';
    public static $tableAlias = 'hnw_customer_phone';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'customer_id',
        'user_id',
        'phone_number',
        'is_primary',
    );

    /**
     * @var string[]
     */
    protected $casts = array(
        'is_primary' => 'bool'
    );

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
     * @author xuanhieupd
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->getAttribute('phone_number');
    }

    /**
     * Thông tin khách hàng
     *
     * @return BelongsTo|Customer
     * @author xuanhieupd
     */
    public function phoneCustomer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

}
