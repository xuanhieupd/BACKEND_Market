<?php

namespace App\Modules\Feed\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Feed\Resources\LikeResource;
use App\Modules\Likeable\Models\Entities\Like;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class FeedLikesController extends AbstractController
{

    /**
     * @param Request $request
     * @return LikeResource[]
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $currentPage = $request->get('page');

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $feedInfo = $request->input('feed');

        $likes = Like::query()
            ->with(array('likeUser'))
            ->where('likeable_type', get_class($feedInfo))
            ->where('likeable_id', $feedInfo->getId())
            ->simplePaginate(20);

        return LikeResource::collection($likes);
    }

}
