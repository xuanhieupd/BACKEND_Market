<?php

namespace App\Modules\Customer\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Customer\Resources\CustomerDetailResource;
use Illuminate\Http\Request;

class DetailController extends AbstractController
{

    /**
     * Chi tiết khách hàng
     *
     * @param Request $request
     * @return CustomerDetailResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $customerInfo = $request->input('customer');

        $customerInfo->load(array(
            'customerGroup',
        ));

        return new CustomerDetailResource($customerInfo);
    }
}

