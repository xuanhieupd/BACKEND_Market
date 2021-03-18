<?php

namespace App\Modules\Likeable\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\User\Models\Entities\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedController extends AbstractController
{

    /**
     * Thích bài viết
     *
     * @param Request $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionLike(Request $request)
    {
        $authorInfo = $this->getAuthor();
        $feedInfo = $request->input('feed');

        return $feedInfo->like($authorInfo) ?
            $this->responseMessage('Đã thích') :
            $this->responseError('Thất bại');
    }

    /**
     * Bỏ thích bài viết
     *
     * @param Request $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionUnlike(Request $request)
    {
        $authorInfo = $this->getAuthor();
        $feedInfo = $request->input('feed');

        return $feedInfo->unlike($authorInfo) ?
            $this->responseMessage('Đã bỏ thích') :
            $this->responseError('Thất bại');
    }

    /**
     * @return User
     */
    protected function getAuthor()
    {
        return Auth::user();
    }

}
