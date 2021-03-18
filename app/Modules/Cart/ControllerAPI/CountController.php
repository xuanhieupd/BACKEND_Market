<?php

namespace App\Modules\Cart\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Cart\Models\Repositories\Contracts\OrderCartInterface;
use App\Modules\Cart\Models\Repositories\Eloquents\OrderCartRepository;
use Illuminate\Http\Request;

class CountController extends AbstractController
{

    /* @var OrderCartRepository */
    private $cartRepo;

    /**
     * Constructor.
     *
     * @param $cartRepo
     * @author xuanhieupd
     */
    public function __construct(OrderCartInterface $cartRepo)
    {
        $this->cartRepo = $cartRepo;
    }

    /**
     * Đếm số lượng sản phẩm có trong giỏ
     *
     * @param Request $request
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {

    }

}
