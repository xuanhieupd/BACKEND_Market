<?php

/**
 * Item Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Order
 * @copyright (c) 03.10.2020, HNW
 */

namespace App\Modules\Order\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;

/* Interfaces */

use App\Modules\Order\Models\Entities\Order;
use App\Modules\Order\Models\Repositories\Contracts\ItemInterface;

/* Entities */

use App\Modules\Order\Models\Entities\Item;
use Illuminate\Support\Collection;

class ItemRepository extends AbstractRepository implements ItemInterface
{

    /**
     * Lấy danh sách sản phẩm có trong toa hàng
     *
     * @param $orderId
     * @return Collection|Item
     * @author xuanhieupd
     */
    public function getItemsByOrderId($orderId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('order_id', $orderId)
            ->get();

    }

    /**
     * Lấy danh sách mã hàng theo OrderId và ProductId
     *
     * @param $orderId
     * @param $productId
     * @return Collection|Item
     * @author xuanhieupd
     */
    public function getItemsByOrderIdAndProductId($orderId, $productId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('order_id', $orderId)
            ->where('product_id', $productId)
            ->get();

    }

    /**
     * Lấy danh sách mã hàng theo OrderId và VariantIds
     *
     * @param $orderId
     * @param $productId
     * @return Collection|Item
     * @author xuanhieupd
     */
    public function getItemsByOrderIdAndVariantIds($orderId, array $variantIds, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('order_id', $orderId)
            ->whereIn('variant_id', $variantIds)
            ->get();

    }

    /**
     * Xóa sản phẩm trong toa hàng
     *
     * @param $orderId
     * @param $productId
     * @return bool
     * @author xuanhieupd
     */
    public function deleteProduct($orderId, $productId)
    {
        return $this->makeModel()
            ->where('order_id', $orderId)
            ->where('product_id', $productId)
            ->delete();
    }

    /**
     * Cập nhật giá sản phẩm
     *
     * @param $orderId
     * @param $productId
     * @param $price
     * @return mixed
     * @author xuanhieupd
     */
    public function updatePrice($orderId, $productId, $price)
    {
        return $this->makeModel()
            ->where('order_id', $orderId)
            ->where('product_id', $productId)
            ->update(array('price' => $price));
    }

    /**
     * Lấy MỘT bản ghi item theo điều kiện $orderId và $productId
     * (Cho case log giá sản phẩm)
     *
     * @param $orderId
     * @param $productId
     * @return Item
     */
    public function getItemByOrderIdAndProductId($orderId, $productId)
    {
        return $this->makeModel()
            ->where('order_id', $orderId)
            ->where('product_id', $productId)
            ->first();
    }

    /**
     * @return Item
     */
    public function model()
    {
        return Item::class;
    }

}
