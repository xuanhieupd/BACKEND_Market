<?php

/**
 * Store Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Store
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Store\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Likeable\Models\Entities\Like;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use App\Modules\Store\Exceptions\StoreNotFoundException;
use App\Modules\Store\Models\Entities\Store;
use App\Modules\Store\Models\Repositories\Contracts\StoreInterface;

class StoreRepository extends AbstractRepository implements StoreInterface
{

    public function getStores(array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions);
    }

    public function getStore(array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions);
    }

    /**
     * Lấy thông tin cửa hàng
     *
     * @param $storeId
     * @param array $conditions
     * @param array $fetchOptions
     * @return $this
     * @author xuanhieupd
     */
    public function getStoreById($storeId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('store_id', $storeId);
    }

    /**
     * Danh sách cửa hàng $userId đang theo dõi
     *
     * @param $authorInfo
     * @param array $conditions
     * @param array $fetchOptions
     * @return $this
     */
    public function getFollowedStores($authorInfo, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->whereLikedBy($authorInfo);
    }

    /**
     * @return Store
     */
    public function model()
    {
        return Store::class;
    }

}
