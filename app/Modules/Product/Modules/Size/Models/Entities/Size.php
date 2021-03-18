<?php

/**
 * Product Size Model
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Size
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Product\Modules\Size\Models\Entities;

use App\Base\AbstractModel;
use App\Modules\Product\Modules\Size\Models\Traits\SizeNestedModel;
use Illuminate\Database\Eloquent\Builder;

class Size extends AbstractModel
{

    use SizeNestedModel;

    protected $connection = 'box';
    protected $table = 'hnw_product_size';
    protected $primaryKey = 'size_id';
    public static $tableAlias = 'hnw_product_size';

    protected $fillable = array();

    /**
     * Alias for `size_id`
     *
     * @return int
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function getId()
    {
        return $this->getAttribute('size_id');
    }

    /**
     * Tên size
     *
     * @return string
     * @author xuanhieupd
     */
    public function getName()
    {
        return $this->getAttribute('title');
    }

    /**
     * Lọc size cửa hàng bật
     *
     * @param Builder $builder
     * @param $storeId
     * @return Builder
     * @author xuanhieupd
     */
    public function scopeStoreAvaiable(Builder $builder, $storeId)
    {
        return $builder
            ->where('store_id', $storeId)
            ->orWhere('store_id', 0);
    }

}
