<?php

/**
 * Customer Model
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Customer
 * @copyright (c) 03.10.2020, HNW
 */

namespace App\Modules\Customer\Models\Entities;

use App\Base\AbstractModel;

use App\GlobalConstants;
use App\Modules\Customer\Modules\Address\Models\Entities\CustomerAddress;
use App\Modules\Customer\Modules\Group\Models\Entities\Group;
use App\Modules\Customer\Modules\PhoneNumber\Models\Entities\CustomerPhone;
use App\Modules\Store\Models\Entities\Store;
use App\Modules\User\Models\Entities\User;
use Bavix\Wallet\Traits\CanPay;
use Bavix\Wallet\Interfaces\Customer as ICustomer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Customer extends AbstractModel implements ICustomer
{

    const CUSTOMER_GUEST = 'GUEST'; // Mã khách vãng lai

    use CanPay;

    protected $table = 'hnw_customer';
    protected $primaryKey = 'customer_id';
    public static $tableAlias = 'hnw_customer';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'store_id',
        'group_id',
        'user_id',
        'code',
        'fullname',
        'rate_number',
    );

    /**
     * Alias for `customer_id`
     *
     * @return int
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function getId()
    {
        return $this->getAttribute('customer_id');
    }

    /**
     * Tên khách hàng
     *
     * @return string
     * @author xuanhieupd
     */
    public function getFullName()
    {
        return $this->getAttribute('fullname');
    }

    /**
     * Ảnh đại diện
     *
     * @return string
     * @author xuanhieupd
     */
    public function getAvatarUrl()
    {
        return 'http://via.placeholder.com/150/385898/385898';
    }

    /**
     * Số điện thoại chính
     *
     * @return string
     * @author xuanhieupd
     */
    public function getPhoneNumber()
    {
        return '0966886218';
    }

    /**
     * Địa chỉ
     *
     * @return string
     * @author xuanhieupd
     */
    public function getAddress()
    {
        return 'Phù Đổng';
    }

    /**
     * Có phải khách vãng lai hay không ?
     *
     * @return bool
     * @author xuanhieupd
     */
    public function isGuest()
    {
        return $this->getAttribute('code') == self::CUSTOMER_GUEST;
    }

    /**
     * Số tiền khách hàng này đang nợ
     * Biểu thị bởi số tiền trong ví < 0
     *
     * @return int
     * @author xuanhieupd
     */
    public function getDebtAmount(): int
    {
        $balance = $this->getBalanceAttribute();
        return $balance < 0 ? abs($balance) : 0;
    }

    /**
     * Số tiền khách hàng có trong ví của mình
     *
     * @return float|int
     * @author xuanhieupd
     */
    public function getBalance(): int
    {
        $balance = $this->getBalanceAttribute();
        return $balance > 0 ? abs($balance) : 0;
    }

    /**
     * Thông tin cửa hàng
     *
     * @return BelongsTo|Store
     * @author xuanhieupd
     */
    public function customerStore()
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    /**
     * Thông tin nhóm khách
     *
     * @return BelongsTo|Group
     * @author xuanhieupd
     */
    public function customerGroup()
    {
        return $this->belongsTo(Group::class, 'group_id', 'group_id')
            ->select(array(
                'group_id',
                'title',
            ));
    }

    /**
     * Thông tin người tạo
     *
     * @return BelongsTo|User
     * @author xuanhieupd
     */
    public function customerUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Danh sách số điện thoại
     *
     * @return HasMany|CustomerPhone
     */
    public function customerPhones()
    {
        return $this->hasMany(CustomerPhone::class, 'customer_id', 'customer_id');
    }

    /**
     * Danh sách số điện thoại
     *
     * @return HasMany|CustomerAddress
     */
    public function customerAddresses()
    {
        return $this->hasMany(CustomerAddress::class, 'customer_id', 'customer_id');
    }

    /**
     * Số điện thoại chính
     *
     * @return HasOne|CustomerPhone
     * @author xuanhieupd
     */
    public function customerPhone()
    {
        return $this->hasOne(CustomerPhone::class, 'customer_id', 'customer_id')
            ->where('is_primary', GlobalConstants::STATUS_ACTIVE)
            ->withDefault();
    }

    /**
     * Địa chỉ chính
     *
     * @return HasOne|CustomerAddress
     * @author xuanhieupd
     */
    public function customerAddress()
    {
        return $this->hasOne(CustomerAddress::class, 'customer_id', 'customer_id')
            ->where('is_primary', GlobalConstants::STATUS_ACTIVE)
            ->withDefault();
    }


}
