<?php
/**
 * Order Middleware
 *
 * @author xuanhieupd
 * @package Order
 * @copyright 04.10.2020, HNW
 */

namespace App\Modules\Order\Middleware;

use App\Modules\Order\Models\Repositories\Contracts\OrderInterface;
use App\Modules\Order\Models\Repositories\Eloquents\OrderRepository;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;

class OrderMiddleware
{

    /**
     * @var OrderRepository
     */
    private $orderRepo;

    /**
     * OrderMiddleware constructor.
     */
    public function __construct(OrderInterface $orderRepo)
    {
        $this->orderRepo = $orderRepo;
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
        $orderId = $request->route('orderId', -1);

        $orderInfo = $this->orderRepo->getUserOrderById(auth()->id(), $orderId)->first();
        if (!$orderInfo) return response()->responseError('Không tìm thấy thông tin toa hàng');

        $request->merge(array('order' => $orderInfo));
        return $next($request);
    }


}
