<?php

namespace App\Modules\Product\Modules\Attachment\ControllerAPI;

use App\Base\AbstractController;
use Illuminate\Http\Request;

class ThumbController extends AbstractController
{

    /**
     * Ảnh đại diện thumbnail
     *
     * @param Request $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        return response()->file(storage_path('thumb.jpg'));
    }

}
