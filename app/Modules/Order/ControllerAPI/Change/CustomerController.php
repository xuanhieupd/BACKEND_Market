<?php

/**
 * Change Customer Controller
 *
 * @author xuanhieupd
 * @package Order
 * @copyright 05.10.2020
 */

namespace App\Modules\Order\ControllerAPI\Change;

use App\Base\AbstractController;
use App\Modules\Order\Requests\Change\CustomerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends AbstractController
{

    /**
     * Thay đổi khách hàng trong toa
     *
     * @param CustomerRequest $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(CustomerRequest $request)
    {
        $orderInfo = $request->input('order');
        $customerId = $request->get('customer_id');

        if ($orderInfo->getCustomerId() == $customerId) {
            return $this->responseMessage('Thay đổi khách hàng thành công');
        }

        DB::beginTransaction();
        try {
            $this->logActivity($request);

            $orderInfo->setAttribute('customer_id', $customerId);
            $orderInfo->save();

            DB::commit();
            return $this->responseMessage('Thay đổi khách hàng thành công');
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            return $this->responseError('Thay đổi khách hàng thất bại');
        }
    }

    /**
     * Log Activity
     *
     * @param Request $request
     * @author xuanhieupd
     */
    public function logActivity(Request $request)
    {
        $orderInfo = $request->input('order');
        $customerId = $request->get('customer_id');

        activity('order.customer')
            ->causedBy(Auth::user())
            ->performedOn($orderInfo)
            ->withProperties(array(
                'oldId' => $orderInfo->getCustomerId(),
                'newId' => $customerId,
            ))
            ->log('updated.customer');
    }

}
