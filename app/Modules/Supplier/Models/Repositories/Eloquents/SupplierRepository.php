<?php

/**
 * Supplier Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Supplier
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Supplier\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Supplier\Exceptions\SupplierNotFoundException;
use App\Modules\Supplier\Models\Entities\Supplier;
use App\Modules\Supplier\Models\Repositories\Contracts\SupplierInterface;
use Illuminate\Support\Collection;

class SupplierRepository extends AbstractRepository implements SupplierInterface
{
    /**
     * Lấy danh sách nhà cung cấp
     *
     * @param $storeId
     * @param array $conditions
     * @param array $fetchOptions
     * @return Collection|Supplier
     * @author xuanhieupd
     */
    public function getStoreSuppliers($storeId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('store_id', $storeId)
            ->simplePaginate(20);
    }

    /**
     * Lấy thông tin nhà cung cấp theo Id
     *
     * @param $storeId
     * @param $supplierId
     * @param array $conditions
     * @param array $fetchOptions
     * @return Supplier
     * @throws SupplierNotFoundException
     * @author xuanhieupd
     */
    public function getStoreSupplierById($storeId, $supplierId, array $conditions = array(), array $fetchOptions = array())
    {
        $supplierInfo = $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('store_id', $storeId)
            ->where('supplier_id', $supplierId)
            ->first();

        if (!$supplierInfo) {
            throw new SupplierNotFoundException();
        }

        return $supplierInfo;
    }

    /**
     * @return Supplier
     */
    public function model()
    {
        return Supplier::class;
    }

}
