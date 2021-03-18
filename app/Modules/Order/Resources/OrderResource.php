<?php

/**
 * Order Resource
 *
 * @author xuanhieupd
 * @package Order
 * @copyright 06.10.2020, HNW
 */

namespace App\Modules\Order\Resources;

use App\Base\AbstractResource;
use App\Modules\Store\Resources\StoreResource;
use App\Modules\User\Resources\UserSimpleResource;
use Illuminate\Http\Request;

class OrderResource extends AbstractResource
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
            'order_id' => $this->getId(),
            'code' => $this->getAttribute('code'),
            'user' => new UserSimpleResource($this->orderUser),
            'store' => new StoreResource($this->orderStore),
            'summary' => array(
                'total_quantity' => $this->getAttribute('total_quantity'),
                'total_price' => $this->getAttribute('total_price'),
            ),
            'changed' => array(
                'quantity' => $this->isChangedQuantity(),
                'price' => $this->isChangedPrice(),
            ),
            'status' => $this->getStatus(),
            'created_date' => $this->getCreatedDate(),
            'updated_date' => $this->getUpdatedDate(),
        );
    }
}
