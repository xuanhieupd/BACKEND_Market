<?php

/**
 * Supplier Model
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Supplier
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Supplier\Models\Entities;

use App\Base\AbstractModel;

use App\GlobalConstants;
use App\Modules\Store\Models\Entities\Store;
use App\Modules\Supplier\Modules\Address\Models\Entities\SupplierAddress;
use App\Modules\Supplier\Modules\PhoneNumber\Models\Entities\SupplierPhone;
use App\Modules\User\Models\Entities\User;
use Bavix\Wallet\Traits\CanPay;
use Bavix\Wallet\Interfaces\Customer as ICustomer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Supplier extends AbstractModel implements ICustomer
{

    use CanPay;

    protected $table = 'hnw_supplier';
    protected $primaryKey = 'supplier_id';
    public static $tableAlias = 'hnw_supplier';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'store_id',
        'supervisor_id',
        'code',
        'fullname',
    );

    /**
     * Alias for `supplier_id`
     *
     * @return int
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function getId()
    {
        return $this->getAttribute('supplier_id');
    }

    /**
     * Tên nhà cung cấp
     *
     * @return string
     * @author xuanhieupd
     */
    public function getFullName()
    {
        return $this->getAttribute('fullname');
    }

    /**
     * Số tiền nhà cung cấp NỢ cửa hàng
     *
     * @return int
     * @author xuanhieupd
     */
    public function getDebtAmount(): int
    {
        $balance = $this->getBalanceAttribute();
        return $balance > 0 ? abs($balance) : 0;
    }

    /**
     * Số tiền cửa hàng NỢ nhà cung cấp
     *
     * @return int
     * @author xuanhieupd
     */
    public function getBalance(): int
    {
        $balance = $this->getBalanceAttribute();
        return $balance < 0 ? abs($balance) : 0;
    }

    /**
     * Thông tin cửa hàng
     *
     * @return BelongsTo|Store
     * @author xuanhieupd
     */
    public function supplierStore()
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    /**
     * Thông tin người tạo
     *
     * @return BelongsTo|User
     * @author xuanhieupd
     */
    public function supplierUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Danh sách số điện thoại
     *
     * @return HasMany|SupplierPhone
     */
    public function supplierPhones()
    {
        return $this->hasMany(SupplierPhone::class, 'supplier_id', 'supplier_id');
    }

    /**
     * Danh sách số điện thoại
     *
     * @return HasMany|SupplierAddress
     */
    public function supplierAddresses()
    {
        return $this->hasMany(SupplierAddress::class, 'supplier_id', 'supplier_id');
    }

    /**
     * Số điện thoại chính
     *
     * @return HasOne|SupplierPhone
     * @author xuanhieupd
     */
    public function supplierPhone()
    {
        return $this->hasOne(SupplierPhone::class, 'supplier_id', 'supplier_id')
            ->where('is_primary', GlobalConstants::STATUS_ACTIVE)
            ->withDefault();
    }

    /**
     * Địa chỉ chính
     *
     * @return HasOne|SupplierAddress
     * @author xuanhieupd
     */
    public function supplierAddress()
    {
        return $this->hasOne(SupplierAddress::class, 'supplier_id', 'supplier_id')
            ->where('is_primary', GlobalConstants::STATUS_ACTIVE)
            ->withDefault();
    }


}
