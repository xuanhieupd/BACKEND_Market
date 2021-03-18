<?php

/**
 * Customer Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Category
 * @copyright (c) 19.11.2020, HNW
 */

namespace App\Modules\Category\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Category\Models\Entities\Category;
use App\Modules\Category\Models\Repositories\Contracts\CategoryInterface;
use Illuminate\Support\Collection;

class CategoryRepository extends AbstractRepository implements CategoryInterface
{

    /**
     * Lấy danh sách danh mục
     *
     * @param array $conditions
     * @param array $fetchOptions
     * @return $this
     * @author xuanhieupd
     */
    public function getCategories(array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions);
    }

    /**
     * Lấy danh mục cửa hàng đang bật
     *
     * @param $storeId
     * @param array $conditions
     * @param array $fetchOptions
     * @return Collection|Category
     * @author xuanhieupd
     */
    public function getStoreCategories($storeId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions);
    }

    /**
     * Find by Id
     *
     * @param $categoryId
     * @param array $conditions
     * @param array $fetchOptions
     * @return Category|null
     */
    public function getCategoryById($categoryId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('category_id', $categoryId);
    }

    /**
     * Danh sách danh mục userId đang follow
     *
     * @param $authorInfo
     * @param array $conditions
     * @param array $fetchOptions
     * @return mixed
     * @author xuanhieupd
     */
    public function getFollowedCategories($authorInfo, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->whereLikedBy($authorInfo);
    }

    /**
     * @return Category
     */
    public function model()
    {
        return Category::class;
    }

}
