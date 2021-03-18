<?php

/**
 * Supplier Address Model
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package SupplierAddress
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Supplier\Modules\Address\Models\Entities;

use App\Base\AbstractModel;
use App\Modules\Supplier\Models\Entities\Supplier;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierAddress extends AbstractModel
{

    protected $table = 'hnw_supplier_address';
    protected $primaryKey = 'address_id';
    public static $tableAlias = 'hnw_supplier_address';

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
     * Thông tin nhà cung cấp
     *
     * @return BelongsTo|Supplier
     * @author xuanhieupd
     */
    public function addressSupplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

}
