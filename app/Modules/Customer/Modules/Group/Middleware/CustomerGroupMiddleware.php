<?php

namespace App\Modules\Customer\Modules\Group\Middleware;

use App\Modules\Customer\Modules\Group\Models\Repositories\Contracts\GroupInterface;
use App\Modules\Customer\Modules\Group\Models\Repositories\Eloquents\GroupRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerGroupMiddleware
{
    /**
     * @var GroupRepository
     */
    private $groupRepo;

    /**
     * Constructor.
     *
     * @param GroupInterface $orderRepo
     * @author xuanhieupd
     */
    public function __construct(GroupInterface $groupRepo)
    {
        $this->groupRepo = $groupRepo;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        $visitor = Auth::user();
        $groupId = $request->route('groupId', -1);

        $groupInfo = $this->groupRepo->getStoreGroupById($visitor->getStoreId(), $groupId);
        if (!$groupInfo) {
            return response()->responseError('Không tìm thấy thông tin nhóm khách hàng');
        }

        $request->merge(array('group' => $groupInfo));
        return $next($request);
    }
}
