<?php

/**
 * Brand Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Brand
 * @copyright (c) 20.11.2020, HNW
 */

namespace App\Modules\Brand\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Brand\Models\Entities\Brand;
use App\Modules\Brand\Models\Repositories\Contracts\BrandInterface;
use Illuminate\Support\Collection;

class BrandRepository extends AbstractRepository implements BrandInterface
{

    /**
     * Lấy danh sách thương hiệu
     *
     * @param $storeId
     * @param array $conditions
     * @param array $fetchOptions
     * @return Collection|Brand
     * @author xuanhieupd
     */
    public function getStoreBrands($storeId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->storeAvaiable($storeId)
            ->simplePaginate(50);
    }

    /**
     * @return Brand
     */
    public function model()
    {
        return Brand::class;
    }

}
