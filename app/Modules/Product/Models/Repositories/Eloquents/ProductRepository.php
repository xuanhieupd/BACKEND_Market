<?php

/**
 * Product Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Product
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Product\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\GlobalConstants;
use App\Modules\Base\Helpers\CollectionHelper;
use App\Modules\Follower\Models\Entities\Follower;
use App\Modules\Product\Exceptions\ProductNotFoundException;
use App\Modules\Product\Models\Entities\Product;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Store\Models\Entities\Store;
use App\Modules\Store\Models\Repositories\Contracts\StoreInterface;
use App\Modules\Store\Models\Repositories\Eloquents\StoreRepository;
use App\Modules\Store\Modules\SettingUser\Constants\Constants;
use App\Modules\Store\Modules\SettingUser\Models\Repositories\Contracts\SettingUserInterface;
use App\Modules\Store\Modules\SettingUser\Models\Repositories\Eloquents\SettingUserRepository;
use Illuminate\Support\Collection;

class ProductRepository extends AbstractRepository implements ProductInterface
{

    /**
     * Lấy danh sách sản phẩm
     *
     * @param array $conditions
     * @param array $fetchOptions
     * @return $this
     * @author xuanhieupd
     */
    public function getProducts(array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions);
    }

    /**
     * Lấy danh sách sản phẩm trong cửa hàng
     *
     * @param $storeId
     * @param array $conditions
     * @param array $fetchOptions
     * @return
     * @author xuanhieupd
     */
    public function getStoreProducts($storeId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('store_id', $storeId)
            ->orderBy('product_id', 'DESC');
    }

    /**
     * Lấy danh sách sản phẩm theo Ids
     *
     * @param array $productIds
     * @return mixed
     * @author xuanhieupd
     */
    public function getProductsByIds(array $productIds, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->whereIn('product_id', $productIds)
            ->get();
    }

    /**
     * Tìm kiếm sản phẩm theo Id
     *
     * @param $storeId
     * @param $productId
     * @param array $conditions
     * @param array $fetchOptions
     * @return Product
     * @throws ProductNotFoundException
     */
    public function getStoreProductById($storeId, $productId, array $conditions = array(), array $fetchOptions = array())
    {
        $productInfo = $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('store_id', $storeId)
            ->where('product_id', $productId)
            ->first();

        if (!$productInfo) {
            throw new ProductNotFoundException();
        }

        return $productInfo;
    }

    /**
     * Tìm kiếm sản phẩm
     *
     * @param $storeId
     * @param $searchQuery
     * @param array $conditions
     * @param array $fetchOptions
     * @return Collection|Product
     * @author xuanhieupd
     */
    public function getStoreSearch($storeId, $searchQuery, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->select(array(
                'product_id',
                'sku',
                'title',
                'current_stock',
                'total_stock',
                'import_price',
                'whole_price',
                'retail_price',
                'collaborator_price',
            ))
            ->where('store_id', $storeId)
            ->search($searchQuery)
            ->simplePaginate(10);
    }

    /**
     * Cập nhật nhà cung cấp với các sản phẩm chưa xác định nhà cung cấp
     *
     * @param $productIds
     * @param $newSupplierId
     * @return bool
     * @author xuanhieupd
     */
    public function bulkUpdateProductSupplier($productIds, $newSupplierId)
    {
        return $this->makeModel()
            ->whereIn('product_id', $productIds)
            ->where('supplier_id', 0)
            ->update(array('supplier_id' => $newSupplierId));
    }

    /**
     * Find by ID
     *
     * @param $productId
     * @param array $conditions
     * @param array $fetchOptions
     * @return Product|null
     */
    public function getProductById($productId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('product_id', $productId);
    }

    /**
     * Danh sách sản phẩm $userId đã yêu thích
     *
     * @param $authorInfo
     * @param array $conditions
     * @param array $fetchOptions
     * @return $this
     */
    public function getFollowedProducts($authorInfo, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->whereLikedBy($authorInfo);
    }

    /**
     * Bind giá vào dữ liệu sản phẩm
     * @param $products
     * @param $userId
     * @return Collection
     */
    public function bindPrice($products, $userId)
    {
        $storeIds = CollectionHelper::pluckUnique($products, 'store_id')->toArray();

        $stores = $this->_getStoreModel()->getStores()->whereIn('store_id', $storeIds)->get();

        $settings = Follower::query()
            ->whereIn('store_id', $storeIds)
            ->where('user_id', $userId)
            ->where('status_id', GlobalConstants::STATUS_ACTIVE)
            ->get();

        foreach ($products as $productInfo) {
            $storeInfo = $stores->where('store_id', $productInfo->getAttribute('store_id'))->first();
            if (!$storeInfo) continue;

            $canViewPrice = $this->canViewPrice($storeInfo, $settings);
            $productInfo->setAttribute('canViewPrice', $canViewPrice);
        }

        return $products;
    }

    /**
     * Bind Price for Product
     *
     * @param Store $storeInfo
     * @param Collection $settings
     * @return int|null
     * @author xuanhieupd
     */
    private function canViewPrice(Store $storeInfo, Collection $settings)
    {
        $settingInfo = $settings->where('store_id', $storeInfo->getId())->first();

        return $settingInfo ?
            Constants::canViewPrice($settingInfo->getAttribute('display_id')) :
            $storeInfo->isPublicPrice();
    }

    /**``
     * @return Product
     */
    public function model()
    {
        return Product::class;
    }

    /**
     * @return StoreRepository
     */
    protected function _getStoreModel()
    {
        return app(StoreInterface::class);
    }

    /**
     * @return SettingUserRepository
     */
    protected function _getSettingUserModel()
    {
        return app(SettingUserInterface::class);
    }

}
