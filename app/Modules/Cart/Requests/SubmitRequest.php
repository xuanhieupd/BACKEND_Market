<?php
/**
 * SubmitRequest
 *
 * @author xuanhieupd
 * @package Cart
 * @copyright 04.10.2020, HNW
 */

namespace App\Modules\Cart\Requests;

use App\Base\AbstractRequest;
use App\Modules\Cart\Models\Repositories\Contracts\OrderCartInterface;
use App\Modules\Cart\Models\Repositories\Eloquents\OrderCartRepository;

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
        return [
        ];
    }

}
