<?php

/**
 * Feed Eloquent Repository
 *
 * @author xuanhieupd
 * @package Feed
 * @copyright (c) 05.04.2020, HNW
 */

namespace App\Modules\Feed\Models\Repositories;

use App\Base\AbstractRepository;
use App\Modules\Feed\Models\Repositories\Contracts\FeedInterface;
use App\Modules\Feed\Models\Entities\Feed;

class FeedRepository extends AbstractRepository implements FeedInterface
{

    /**
     * Lấy danh sách bảng tin
     *
     * @param array $conditions
     * @param array $fetchOptions
     * @return $this
     * @author xuanhieupd
     */
    public function getFeeds(array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions);
    }

    /**
     * Lấy một bản ghi bảng tin
     *
     * @param array $conditions
     * @param array $fetchOptions
     * @return $this
     * @author xuanhieupd
     */
    public function getFeed(array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions);
    }

    /**
     * Lấy thông tin bảng tin theo Id
     *
     * @param int $feedId
     * @param array $conditions
     * @param array $fetchOptions
     * @return $this
     * @author xuanhieupd
     */
    public function getFeedById($feedId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->getFeed($conditions, $fetchOptions)->where('feed_id', $feedId);
    }

    /**
     * @return string
     */
    public function model()
    {
        return Feed::class;
    }
}
