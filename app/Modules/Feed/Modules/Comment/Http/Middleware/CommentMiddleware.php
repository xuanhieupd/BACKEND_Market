<?php

/**
 * Comment Middleware
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Comment
 * @copyright (c) 09.10.2020, HNW
 */

namespace App\Modules\Feed\Modules\Comment\Http\Middleware;

use App\Modules\Feed\Modules\Comment\Models\Repositories\CommentRepository;
use App\Modules\Feed\Modules\Comment\Models\Repositories\Contracts\CommentInterface;
use Closure;
use Illuminate\Support\Facades\Auth;

class CommentMiddleware
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
     * Handle an incoming request.
     *
     * @param  $request
     * @param Closure $next
     * @return mixed
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function handle($request, Closure $next)
    {
        $commentIdFromInput = $request->route('commentId');
        $visitor = Auth::user();

        $commentInfo = $this->commentRepo->getAuthorCommentById($visitor, $commentIdFromInput)->first();
        if (!$commentInfo) return response()->responseError('Không tìm thấy thông tin bình luận', 400);

        $request->merge(array('comment' => $commentInfo));
        return $next($request);
    }

}
