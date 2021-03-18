<?php

/**
 * Supplier Phone Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package PhoneNumber
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Supplier\Modules\PhoneNumber\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Supplier\Modules\PhoneNumber\Models\Entities\SupplierPhone;
use App\Modules\Supplier\Modules\PhoneNumber\Models\Repositories\Contracts\SupplierPhoneInterface;

class SupplierPhoneRepository extends AbstractRepository implements SupplierPhoneInterface
{

    /**
     * @return SupplierPhone
     */
    public function model()
    {
        return SupplierPhone::class;
    }

}
