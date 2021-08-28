<?php

/**
 * Orders Controller
 *
 * @author xuanhieupd
 * @package Order
 * @copyright 04.10.2020, HNW
 */

namespace App\Modules\Order\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Order\Models\Repositories\Contracts\OrderInterface;
use App\Modules\Order\Models\Repositories\Eloquents\OrderRepository;
use App\Modules\Order\Resources\OrderResource;
use App\Modules\User\Models\Repositories\Contracts\UserInterface;
use App\Modules\User\Models\Repositories\Eloquents\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class OrdersController extends AbstractController
{

    /**
     * @var OrderRepository
     */
    private $orderRepo;

    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * Constructor.
     *
     * @param OrderRepository $orderRepo
     * @author xuanhieupd
     */
    public function __construct(
        OrderInterface $orderRepo,
        UserInterface $userRepo
    )
    {
        $this->orderRepo = $orderRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * Danh sách toa hàng
     *
     * @param Request $request
     * @return OrderResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $currentPage = $request->get('page');

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $orders = $this->orderRepo->getUserOrders(auth()->id(), array(), array())
            ->filter($request->all())
            ->with(array('orderStore'))
            ->orderBy('order_id', 'DESC')
            ->simplePaginate(10);

        return OrderResource::collection($orders);
    }


}
