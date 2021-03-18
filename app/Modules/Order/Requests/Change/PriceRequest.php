<?php

/**
 * Change Price Request
 *
 * @author xuanhieupd
 * @package Order
 * @copyright 08.10.2020, HNW
 */

namespace App\Modules\Order\Requests\Change;

use App\Base\AbstractRequest;

class PriceRequest extends AbstractRequest
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
            'product_id' => 'required|numeric',
            'price' => 'required|numeric',
        );
    }


}
