<?php

namespace App\Modules\Product\Modules\Attachment\ControllerAPI;

use App\Base\AbstractController;
use Illuminate\Http\Request;

class FullController extends AbstractController
{

    /**
     * Ảnh đại diện full
     *
     * @param Request $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        return response()->file(storage_path('full.jpg'));
    }

}
