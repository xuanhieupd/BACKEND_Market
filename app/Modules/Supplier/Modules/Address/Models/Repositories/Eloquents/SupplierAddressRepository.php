<?php

/**
 * Supplier Address Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package SupplierAddress
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Supplier\Modules\Address\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Supplier\Modules\Address\Models\Entities\SupplierAddress;
use App\Modules\Supplier\Modules\Address\Models\Repositories\Contracts\SupplierAddressInterface;

class SupplierAddressRepository extends AbstractRepository implements SupplierAddressInterface
{

    /**
     * @return SupplierAddress
     */
    public function model()
    {
        return SupplierAddress::class;
    }

}
