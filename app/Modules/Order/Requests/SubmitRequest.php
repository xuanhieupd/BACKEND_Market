<?php

/**
 * Submit Request
 *
 * @author xuanhieupd
 * @package Order
 * @copyright 05.10.2020, HNW
 */

namespace App\Modules\Order\Requests;

use App\Base\AbstractRequest;
use App\Modules\Order\Models\Entities\Order;
use Illuminate\Contracts\Validation\Validator;

class SubmitRequest extends AbstractRequest
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
            'total_receivable' => 'numeric',
            'total_expense' => 'numeric',
            'money_cash' => 'numeric',
            'money_banking' => 'numeric',
        );
    }


}
