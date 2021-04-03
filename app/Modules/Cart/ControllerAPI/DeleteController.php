<?php

namespace App\Modules\Cart\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Cart\Models\Repositories\Contracts\OrderCartInterface;
use App\Modules\Cart\Models\Repositories\Eloquents\OrderCartRepository;
use Illuminate\Http\Request;

class DeleteController extends AbstractController
{

    /* @var OrderCartRepository */
    private $cartRepo;

    /**
     * Constructor
     *
     * @param OrderCartInterface $cartRepo
     * @author xuanhieupd
     */
    public function __construct(OrderCartInterface $cartRepo)
    {
        $this->cartRepo = $cartRepo;
    }

    /**
     * Xóa sản phẩm trong giỏ
     *
     * @param Request $request
     * @return mixed
     * @throws \Exception
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $this->cartRepo->getCarts(auth()->id())
            ->where('product_id', $request->get('product_id', -1))
            ->delete();

        return $this->responseMessage('Thành công');
    }

}
