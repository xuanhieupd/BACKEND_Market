<?php

/**
 * Product Color Model
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Color
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Product\Modules\Color\Models\Entities;

use App\Base\AbstractModel;
use App\Modules\Product\Modules\Color\Models\Traits\ColorNestedModel;
use Illuminate\Database\Eloquent\Builder;

class Color extends AbstractModel
{

    use ColorNestedModel;

    protected $connection = 'box';
    protected $table = 'hnw_product_color';
    protected $primaryKey = 'color_id';
    public static $tableAlias = 'hnw_product_color';

    /**
     * @var array
     */
    protected $fillable = array(
        'store_id',
        'hex_color',
        'title',
        'parent_id',
        'lgt',
        'rgt',
        'sort_order',
    );

    /**
     * @var string[]
     */
    protected $hidden = array(
        'parent_id',
        'lgt',
        'rgt',
    );

    /**
     * Alias for `color_id`
     *
     * @return int
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function getId()
    {
        return $this->getAttribute('color_id');
    }

    /**
     * Tên màu
     *
     * @return string
     * @author xuanhieupd
     */
    public function getName()
    {
        return $this->getAttribute('title');
    }

    /**
     * Hex Color
     *
     * @return string
     * @author xuanhieupd
     */
    public function getHexColor()
    {
        return $this->getAttribute('hex_color');
    }

    /**
     * Lọc màu cửa hàng bật
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
