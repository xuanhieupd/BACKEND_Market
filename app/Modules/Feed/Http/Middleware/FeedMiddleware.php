<?php

/**
 * Feed Middleware
 *
 * @author xuanhieupd
 * @package Feed
 * @copyright (c) 08.10.2020, HNW
 */

namespace App\Modules\Feed\Http\Middleware;

use App\Modules\Feed\Models\Repositories\Contracts\FeedInterface;
use Closure;
use Illuminate\Http\Request;

class FeedMiddleware
{

    protected $feedRepo;

    /**
     * Constructor.
     *
     * @param FeedInterface $feedRepo
     * @author xuanhieupd
     */
    public function __construct(FeedInterface $feedRepo)
    {
        $this->feedRepo = $feedRepo;
    }

    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param \Closure $next
     * @return mixed
     * @author xuanhieupd
     */
    public function handle($request, Closure $next)
    {
        $feedIdFromInput = $request->route('feedId');
        $feedInfo = $this->feedRepo->getFeedById($feedIdFromInput)->first();
        if (!$feedInfo) return response()->responseError('Không tìm thấy thông tin bảng tin', 400);

        $request->merge(array('feed' => $feedInfo));
        return $next($request);
    }

}
