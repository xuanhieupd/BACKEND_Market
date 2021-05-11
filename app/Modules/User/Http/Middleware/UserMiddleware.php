<?php

namespace App\Modules\User\Http\Middleware;

use App\Modules\User\Models\Repositories\Contracts\UserInterface;
use Closure;
use Illuminate\Http\Request;

class UserMiddleware
{

    /**
     * @var UserInterface
     */
    private $userRepo;

    /**
     * Constructor.
     *
     * @param UserInterface $userRepo
     * @author xuanhieupd
     */
    public function __construct(UserInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $userIdFromInput = $request->route('userId', -1);

        $attachmentInfo = $this->userRepo->find($userIdFromInput);
        if (!$attachmentInfo) return response()->responseError('Không tìm thấy thông tin');

        $request->merge(array('user' => $attachmentInfo));
        return $next($request);
    }

}
