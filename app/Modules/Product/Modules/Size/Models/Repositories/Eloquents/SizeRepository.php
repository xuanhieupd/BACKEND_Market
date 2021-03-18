<?php

/**
 * Product Size Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Size
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Product\Modules\Size\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Product\Modules\Color\Models\Entities\Color;
use App\Modules\Product\Modules\Size\Models\Entities\Size;
use App\Modules\Product\Modules\Size\Models\Repositories\Contracts\SizeInterface;
use Illuminate\Support\Collection;

class SizeRepository extends AbstractRepository implements SizeInterface
{

    /**
     * Lấy danh sách size
     *
     * @param $storeId
     * @param array $conditions
     * @param array $fetchOptions
     * @return Collection|Color
     */
    public function getStoreSizes($storeId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->storeAvaiable($storeId)
            ->get();
    }

    /**
     * @return Size
     */
    public function model()
    {
        return Size::class;
    }

}
