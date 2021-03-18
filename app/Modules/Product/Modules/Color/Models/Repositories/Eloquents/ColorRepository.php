<?php

/**
 * Product Color Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Color
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Product\Modules\Color\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Product\Modules\Color\Models\Entities\Color;
use App\Modules\Product\Modules\Color\Models\Repositories\Contracts\ColorInterface;
use Illuminate\Support\Collection;

class ColorRepository extends AbstractRepository implements ColorInterface
{

    /**
     * Lấy danh sách màu
     *
     * @param $storeId
     * @param array $conditions
     * @param array $fetchOptions
     * @return Collection|Color
     */
    public function getStoreColors($storeId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->storeAvaiable($storeId)
            ->orderBy('sort_order', 'ASC')
            ->get();
    }

    /**
     * @return Color
     */
    public function model()
    {
        return Color::class;
    }

}
