<?php

/**
 * Customer Address Model
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package CustomerAddress
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Customer\Modules\Address\Models\Entities;

use App\Base\AbstractModel;
use App\Modules\Customer\Models\Entities\Customer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAddress extends AbstractModel
{

    protected $table = 'hnw_customer_address';
    protected $primaryKey = 'address_id';
    public static $tableAlias = 'hnw_customer_address';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'customer_id',
        'user_id',
        'address',
        'is_primary',
    );

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
     * Thông tin khách hàng
     *
     * @return BelongsTo|Customer
     * @author xuanhieupd
     */
    public function addressCustomer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

}
