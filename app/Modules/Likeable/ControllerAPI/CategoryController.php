<?php

namespace App\Modules\Likeable\ControllerAPI;

use App\Base\AbstractController;
use Illuminate\Http\Request;

class CategoryController extends AbstractController
{

    /**
     * Thêm vào danh sách theo dõi
     *
     * @param Request $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionLike(Request $request)
    {
        $categoryInfo = $request->input('category');

        return $categoryInfo->like() ?
            $this->responseMessage('Đã thêm') :
            $this->responseError('Thất bại');
    }

    /**
     * Xóa khỏi danh sách theo dõi
     *
     * @param Request $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionUnlike(Request $request)
    {
        $categoryInfo = $request->input('category');

        return $categoryInfo->unlike() ?
            $this->responseMessage('Đã xóa') :
            $this->responseError('Thất bại');
    }

}
