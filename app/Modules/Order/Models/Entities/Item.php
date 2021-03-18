<?php

/**
 * Order Item Model
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package User
 * @copyright (c) 03.10.2020, HNW
 */

namespace App\Modules\Order\Models\Entities;

use App\Base\AbstractModel;

use App\Modules\Order\Models\Traits\ItemActivity;
use App\Modules\Product\Models\Services\Contracts\AlterStockInterface;
use App\Modules\Product\Modules\Variant\Models\Entities\Variant;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Product;
use App\Modules\Order\Models\Traits\ItemWallet;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends AbstractModel implements Product, AlterStockInterface
{

    use HasWallet;
    use ItemWallet;
    use ItemActivity;

    protected $table = 'hnw_order_item';
    protected $primaryKey = 'item_id';
    public static $tableAlias = 'hnw_order_item';

    protected $fillable = array(
        'order_id',
        'product_id',
        'variant_id',
        'quantity',
        'price',
    );

    /**
     * Alias for `item_id`
     *
     * @return int
     * @author xuanhieupd
     */
    public function getId()
    {
        return $this->getAttribute('item_id');
    }

    /**
     * Giá trị số lượng sẽ trừ
     *
     * @return int
     * @author xuanhieupd
     */
    public function getAlterStockValue(): int
    {
        return (int)$this->getAttribute('quantity');
    }

    /**
     * Variant Id thay thay đổi số lượng
     *
     * @return int
     * @author xuanhieupd
     */
    public function getVariantId()
    {
        return $this->getAttribute('variant_id');
    }

    /**
     * Số lượng
     *
     * @return int
     * @author xuanhieupd
     */
    public function getQuantity()
    {
        return $this->getAttribute('quantity');
    }

    /**
     * Đơn giá
     *
     * @return int
     * @author xuanhieupd
     */
    public function getPrice()
    {
        return $this->getAttribute('price');
    }

    /**
     * Tổng giá của bản ghi này
     *
     * @return float|int
     * @author xuanhieupd
     */
    public function getTotalPrice()
    {
        return $this->getQuantity() * $this->getPrice();
    }

    /**
     * Thông tin toa hàng
     *
     * @return BelongsTo|Order
     * @author xuanhieupd
     */
    public function itemOrder()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    /**
     * Thông tin mã hàng
     *
     * @return BelongsTo
     * @author xuanhieupd
     */
    public function itemVariant()
    {
        return $this->belongsTo(Variant::class, 'variant_id', 'variant_id');
    }


}
