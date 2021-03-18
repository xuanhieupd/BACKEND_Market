<?php

/**
 * Change Delete Request
 *
 * @author xuanhieupd
 * @package Order
 * @copyright 09.10.2020, HNW
 */

namespace App\Modules\Order\Requests\Change;

use App\Base\AbstractRequest;

class DeleteRequest extends AbstractRequest
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
        );
    }


}
