<?php

/**
 * Delete Controller
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Comment
 * @copyright (c) 09.10.2020, HNW
 */

namespace App\Modules\Feed\Modules\Comment\ControllerAPI;

use App\Base\AbstractController;
use Illuminate\Support\Facades\Auth;

class DeleteController extends AbstractController
{

    /**
     * Xóa bình luận
     *
     * @param \Illuminate\Http\Request $request
     * @return
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function actionIndex(\Illuminate\Http\Request $request)
    {
        $visitor = Auth::user();
        $commentInfo = $request->input('comment');

        return $commentInfo->delete() ? $this->responseMessage('Đã xóa bình luận') : $this->responseError('Xóa thất bại');
    }

}
