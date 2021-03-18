<?php

/**
 * Ok Warehouse Controller
 *
 * @author xuanhieupd
 * @package Order
 * @copyright 05.10.2020
 */

namespace App\Modules\Order\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Order\Models\Entities\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OkWarehouseController extends AbstractController
{

    /**
     * Kho xác nhận nhặt hàng thành công
     *
     * @param Request $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $visitor = Auth::user();
        $orderInfo = $request->input('order');

        DB::beginTransaction();
        try {
            $orderInfo->setAtttribute('warehouse_id', $visitor->getId());
            $orderInfo->setAttribute('status', Order::ORDER_WAREHOUSE_CONFIRM);
            $orderInfo->save();

            DB::commit();
            return $this->responseMessage('Đã xác nhận thành công');
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            return $this->responseError('Xác nhận nhặt hàng thất bại');
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

        activity('order.warehouse.confirm')
            ->causedBy(Auth::user())
            ->performedOn($orderInfo)
            ->log('updated.action');
    }


}
