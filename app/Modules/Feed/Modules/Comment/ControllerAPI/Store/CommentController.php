<?php


namespace App\Modules\Feed\Modules\Comment\ControllerAPI\Store;

use App\Modules\Feed\Modules\Comment\ControllerAPI\CommentController as BaseCommentController;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class CommentController extends BaseCommentController
{

    /**
     * @return Authenticatable
     */
    protected function getAuthor()
    {
        $userInfo = Auth::user();

        return $userInfo->userStore;
    }

}
