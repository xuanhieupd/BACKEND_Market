<?php

/**
 * Product Variant Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Variant
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Product\Modules\Variant\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Product\Modules\Variant\Models\Entities\Variant;
use App\Modules\Product\Modules\Variant\Models\Repositories\Contracts\VariantInterface;
use Illuminate\Support\Collection;

class VariantRepository extends AbstractRepository implements VariantInterface
{

    /**
     * Lấy danh sách mã hàng
     *
     * @param array $conditions
     * @param array $fetchOptions
     * @return $this
     * @author xuanhieupd
     */
    public function getVariants(array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions);
    }

    /**
     * Lấy danh sách mã hàng
     *
     * @param $variantIds
     * @return Collection|Variant
     * @author xuanhieupd
     */
    public function getVariantsByIds($variantIds, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->whereIn('variant_id', $variantIds)
            ->get();
    }

    /**
     * @return Variant
     */
    public function model()
    {
        return Variant::class;
    }

}
