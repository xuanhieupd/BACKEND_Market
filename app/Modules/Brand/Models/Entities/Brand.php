<?php

/**
 * Brand Model
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Brand
 * @copyright (c) 20.11.2020, HNW
 */

namespace App\Modules\Brand\Models\Entities;

use App\Base\AbstractModel;
use Illuminate\Database\Eloquent\Builder;

class Brand extends AbstractModel
{

    protected $table = 'hnw_brand';
    protected $primaryKey = 'brand_id';
    public static $tableAlias = 'hnw_brand';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'store_id',
        'title',
        'status'
    );

    protected $casts = array();

    /**
     * Alias for `brand_id`
     *
     * @return int
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function getId()
    {
        return $this->getAttribute('brand_id');
    }

    /**
     * Lọc thương hiệu của cửa hàng
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
