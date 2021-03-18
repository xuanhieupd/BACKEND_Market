<?php

namespace App\Modules\Likeable\ControllerAPI;

use App\Base\AbstractController;
use App\GlobalConstants;
use Illuminate\Http\Request;

class ProductController extends AbstractController
{

    /**
     * Thêm vào danh sách yêu thích
     *
     * @param Request $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionLike(Request $request)
    {
        $productInfo = $request->input('product');

        return $productInfo->like() ?
            $this->responseMessage('Đã thêm') :
            $this->responseError('Thất bại');
    }

    /**
     * Xóa khỏi danh sách yêu thích
     *
     * @param Request $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionUnlike(Request $request)
    {
        $productInfo = $request->input('product');

        return $productInfo->unlike() ?
            $this->responseMessage('Đã xóa') :
            $this->responseError('Thất bại');
    }

}
