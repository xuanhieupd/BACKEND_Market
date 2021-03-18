<?php

namespace App\Modules\Store\Middleware;

use App\Modules\Store\Models\Repositories\Contracts\StoreInterface;
use App\Modules\Store\Models\Repositories\Eloquents\StoreRepository;
use Illuminate\Http\Request;
use Closure;

class StoreMiddleware
{
    /**
     * @var StoreRepository
     */
    private $storeRepo;

    /**
     * Constructor.
     *
     * @param StoreInterface $storeRepo
     * @author xuanhieupd
     */
    public function __construct(StoreInterface $storeRepo)
    {
        $this->storeRepo = $storeRepo;
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
        $storeId = $request->route('storeId', -1);

        $storeInfo = $this->storeRepo->getStoreById($storeId)->first();
        if (!$storeInfo) return response()->responseError('Không tìm thấy thông tin cửa hàng');

        $request->merge(array('store' => $storeInfo));
        return $next($request);
    }
}
