<?php

namespace App\Modules\Customer\Modules\Group\ControllerAPI;

use App\Base\AbstractController;
use Illuminate\Http\Request;

class DeleteController extends AbstractController
{

    /**
     * Xóa nhóm khách hàng
     *
     * @param Request $request
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $groupInfo = $request->input('group');
    }

}
