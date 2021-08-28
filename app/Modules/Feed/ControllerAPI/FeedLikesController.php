<?php

namespace App\Modules\Feed\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Feed\Resources\LikeResource;
use Illuminate\Http\Request;

class FeedLikesController extends AbstractController
{

    /**
     * @param Request $request
     * @return LikeResource[]
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $feedInfo = $request->input('feed');
        $feedInfo->load(array('likes', 'likes.likeUser'));

        return LikeResource::collection($feedInfo->likes);
    }

}
