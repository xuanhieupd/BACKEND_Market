<?php

/**
 * Order Cart Model
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Cart
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Cart\Models\Entities;

use App\Base\AbstractModel;
use App\Modules\Product\Models\Entities\Product;
use App\Modules\Product\Modules\Variant\Models\Entities\Variant;
use App\Modules\User\Models\Entities\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderCart extends AbstractModel
{

    protected $table = 'hnw_shopping_cart';
    protected $primaryKey = 'cart_id';
    public static $tableAlias = 'hnw_shopping_cart';

    protected $fillable = array(
        'user_id',
        'store_id',
        'product_id',
        'variant_id',
        'quantity',
        'price'
    );

    protected $casts = array(
        'quantity' => 'int',
        'price' => 'int',
    );

    /**
     * Alias for `cart_id`
     *
     * @return int
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function getId()
    {
        return $this->getAttribute('cart_id');
    }

    /**
     * Tổng giá của row này
     *
     * @return int
     * @author xuanhieupd
     */
    public function getTotalPriceAttribute()
    {
        $quantity = $this->getAttribute('quantity');
        $price = $this->getAttribute('price');

        return $quantity * $price;
    }

    /**
     * Thông tin giỏ hàng
     *
     * @return BelongsTo|User
     * @author xuanhieupd
     */
    public function cartUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Thông tin mã hàng
     *
     * @return BelongsTo|Variant
     * @author xuanhieupd
     */
    public function cartVariant()
    {
        return $this->belongsTo(Variant::class, 'variant_id', 'variant_id');
    }

    /**
     * Thông tin sản phẩm
     *
     * @return BelongsTo|Product
     * @author xuanhieupd
     */
    public function cartProduct()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

}
