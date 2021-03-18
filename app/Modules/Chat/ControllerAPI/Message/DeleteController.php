<?php

namespace App\Modules\Chat\ControllerAPI\Message;

use App\Base\AbstractController;
use Illuminate\Http\Request;

class DeleteController extends AbstractController
{

    /**
     * Xóa tin nhắn
     *
     * @param Request $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        return $this->responseMessage('Đã xóa');
    }

}
