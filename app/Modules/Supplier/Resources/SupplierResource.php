<?php

namespace App\Modules\Supplier\Resources;

use App\Base\AbstractResource;
use Illuminate\Http\Request;

class SupplierResource extends AbstractResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     * @author xuanhieupd
     */
    public function toArray($request)
    {
        return array(
            'supplier_id' => $this->getId(),
            'fullname' => $this->getFullName(),
        );
    }

}
