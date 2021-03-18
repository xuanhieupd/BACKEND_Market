<?php

/**
 * To Warehouse Request
 *
 * @author xuanhieupd
 * @package Order
 * @copyright 05.10.2020, HNW
 */

namespace App\Modules\Order\Requests;

use App\Base\AbstractRequest;

class ToWarehouseRequest extends AbstractRequest
{
    /**
     * Rules
     *
     * @return array
     * @author xuanhieupd
     */
    public function rules()
    {
        return array(
            'deposit' => 'numeric',
        );
    }


}
