<?php

namespace App\Modules\Likeable\Models\Repositories;

use App\Base\AbstractRepository;
use App\Modules\Likeable\Models\Entities\Like;
use App\Modules\Likeable\Models\Repositories\Contracts\LikeInterface;
use App\Modules\Store\Models\Entities\Store;

class LikeRepository extends AbstractRepository implements LikeInterface
{

    /**
     * @return $this
     */
    public function getLikes()
    {
        return $this->makeModel();
    }

    /**
     * @param $likeIds
     * @return $this
     */
    public function getLikesByIds($likeIds)
    {
        return $this->makeModel()->whereIn('id', $likeIds);
    }

    /**
     * Danh sách người dùng thích cửa hàng
     *
     * @param $storeId
     * @return $this
     * @author xuanhieupd
     */
    public function getUsersLikeStore($storeId)
    {
        return $this->makeModel()
            ->where('likeable_type', Store::class)
            ->where('likeable_id', $storeId);
    }

    /**
     * @return string
     */
    public function model()
    {
        return Like::class;
    }
}