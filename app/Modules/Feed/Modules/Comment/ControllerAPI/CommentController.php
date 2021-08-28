<?php

/**
 * Comment Controller
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Comment
 * @copyright (c) 08.10.2020, HNW
 */

namespace App\Modules\Feed\Modules\Comment\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Feed\Modules\Comment\Requests\CommentRequest;
use App\Modules\Feed\Modules\Comment\Resources\CommentResource;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use App\Modules\Feed\Modules\Comment\Models\Entities\Comment;

class CommentController extends AbstractController
{

    /**
     * Bình luận vào tin
     *
     * @param CommentRequest $request
     * @return mixed
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function actionIndex(CommentRequest $request)
    {
        $authorInfo = $this->getAuthor();
        $feedInfo = $request->input('feed');

        $commentInfo = new Comment(array(
            'feed_id' => $feedInfo->getId(),
            'author_type' => get_class($authorInfo),
            'author_id' => $authorInfo->getId(),
            'message' => $request->get('message'),
        ));

        $commentInfo->save();
        $commentInfo->load('commentAuthor');

        return new CommentResource($commentInfo);
    }

    /**
     * @return Authenticatable
     */
    protected function getAuthor()
    {
        return Auth::user();
    }
}
