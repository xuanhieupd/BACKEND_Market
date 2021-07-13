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
use App\Modules\Product\Resources\ProductResource;

class OrderDetailResource extends AbstractResource
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
        $orderInfo = $this->resource['order'];

        return array(
            'order_id' => $orderInfo->getId(),
            'code' => $orderInfo->getAttribute('bill_code'),
            'user' => new UserSimpleResource($orderInfo->orderUser),
            'store' => new StoreResource($orderInfo->orderStore),
            'products' => ProductResource::collection($this->resource['products']),
            'datas' => new PickedsResource($this->resource['pickeds']),
            'price' => array(
                'money_cash' => $orderInfo->getAttribute('money_cash'),
                'money_banking' => $orderInfo->getAttribute('money_banking'),
                'total_expense' => $orderInfo->getAttribute('total_expense'),
                'total_receivable' => $orderInfo->getAttribute('total_receivable'),
            ),
            'summary' => array(
                'total_quantity' => $orderInfo->getAttribute('total_quantity'),
                'total_price' => $orderInfo->getAttribute('total_price'),
            ),
            'changed' => array(
                'quantity' => $orderInfo->isChangedQuantity(),
                'price' => $orderInfo->isChangedPrice(),
            ),
            'status' => $orderInfo->getStatus(),
            'created_date' => $orderInfo->getCreatedDate(),
            'updated_date' => $orderInfo->getUpdatedDate(),
        );
    }
}
