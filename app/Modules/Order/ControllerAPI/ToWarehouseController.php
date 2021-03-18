<?php

/**
 * To Warehouse Controller
 *
 * @author xuanhieupd
 * @package Order
 * @copyright 05.10.2020
 */

namespace App\Modules\Order\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Order\Models\Entities\Order;
use App\Modules\Order\Requests\ToWarehouseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ToWarehouseController extends AbstractController
{

    /**
     * Gửi tới kho để nhặt hàng
     *
     * @param ToWarehouseRequest $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(ToWarehouseRequest $request)
    {
        $orderInfo = $request->input('order');
        $depositValue = $request->get('deposit', 0);

        DB::beginTransaction();
        try {
            $orderInfo->setAttribute('money_deposit', $depositValue);
            $orderInfo->setAttribute('status', Order::ORDER_PENDING_WAREHOUSE);
            $orderInfo->save();

            DB::commit();
            return $this->responseMessage('Đã gửi tới kho thành công');
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            return $this->responseError('Gửi tới kho thất bại');
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

        activity('order.to.warehouse')
            ->performedOn($orderInfo)
            ->causedBy(Auth::user())
            ->log('updated.action');
    }

}
