<?php

/**
 * Comments Controller
 *
 * @author xuanhieupd
 * @package Comment
 * @copyright (c) 06.10.2020, HNW
 */

namespace App\Modules\Feed\Modules\Comment\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Feed\Modules\Comment\Models\Repositories\CommentRepository;
use App\Modules\Feed\Modules\Comment\Models\Repositories\Contracts\CommentInterface;
use App\Modules\Feed\Modules\Comment\Resources\CommentResource;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class CommentsController extends AbstractController
{

    /**
     * @var CommentRepository
     */
    protected $commentRepo;

    /**
     * Constructor.
     *
     * @param CommentInterface $commentRepo
     * @author xuanhieupd
     */
    public function __construct(CommentInterface $commentRepo)
    {
        $this->commentRepo = $commentRepo;
    }

    /**
     * Danh sách bình luận
     *
     * @param Request $request
     * @return CommentsResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $currentPage = $request->get('page');

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $feedInfo = $request->input('feed');

        $comments = $this->commentRepo->getCommentsByFeedId($feedInfo->getId())
            ->with('commentAuthor')
            ->simplePaginate(20);

        return CommentResource::collection($comments);
    }

}
