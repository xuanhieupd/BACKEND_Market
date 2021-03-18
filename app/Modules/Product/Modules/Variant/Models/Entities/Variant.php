<?php

/**
 * Product Variant Model
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Variant
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Product\Modules\Variant\Models\Entities;

use App\Base\AbstractModel;
use App\Modules\Product\Models\Entities\Product;
use App\Modules\Product\Modules\Color\Models\Entities\Color;
use App\Modules\Product\Modules\Size\Models\Entities\Size;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Variant extends AbstractModel
{

    protected $connection = 'box';
    protected $table = 'hnw_product_variant';
    protected $primaryKey = 'variant_id';
    public static $tableAlias = 'hnw_product_variant';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'store_id',
        'product_id',
        'color_id',
        'size_id',
        'current_stock',
        'total_stock',
    );

    /**
     * Alias for `variant_id`
     *
     * @return int
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function getId()
    {
        return $this->getAttribute('variant_id');
    }

    /**
     * Thông tin sản phẩm
     *
     * @return BelongsTo|Product
     * @author xuanhieupd
     */
    public function variantProduct()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    /**
     * Thông tin màu
     *
     * @return BelongsTo|Color
     * @author xuanhieupd
     */
    public function variantColor()
    {
        return $this->belongsTo(Color::class, 'color_id', 'color_id')
            ->select(array(
                'color_id',
                'hex_color',
                'title',
            ))
            ->withDefault();
    }

    /**
     * Thông tin size
     *
     * @return BelongsTo|Size
     * @author xuanhieupd
     */
    public function variantSize()
    {
        return $this->belongsTo(Size::class, 'size_id', 'size_id')
            ->select(array(
                'size_id',
                'title',
            ))
            ->withDefault();
    }

}
