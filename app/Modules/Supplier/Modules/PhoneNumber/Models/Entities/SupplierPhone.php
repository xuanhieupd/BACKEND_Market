<?php

/**
 * Supplier Phone Model
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package PhoneNumber
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Supplier\Modules\PhoneNumber\Models\Entities;

use App\Base\AbstractModel;
use App\Modules\Supplier\Models\Entities\Supplier;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierPhone extends AbstractModel
{

    protected $table = 'hnw_supplier_phone';
    protected $primaryKey = 'phone_id';
    public static $tableAlias = 'hnw_supplier_phone';

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
     * Thông tin nhà cung cấp
     *
     * @return BelongsTo|Supplier
     * @author xuanhieupd
     */
    public function phoneSupplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

}
