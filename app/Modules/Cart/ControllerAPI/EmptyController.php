<?php

namespace App\Modules\Cart\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Cart\Models\Repositories\Contracts\OrderCartInterface;
use App\Modules\Cart\Models\Repositories\Eloquents\OrderCartRepository;
use App\Modules\Cart\Requests\EmptyRequest;
use Illuminate\Http\Request;

class EmptyController extends AbstractController
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
     * Xóa toàn bộ hàng trong giỏ
     *
     * @param EmptyRequest $request
     * @return mixed
     * @throws \Exception
     * @author xuanhieupd
     */
    public function actionIndex(EmptyRequest $request)
    {
        $isDeleted = $this->cartRepo
            ->getCarts(auth()->id())
            ->whereIn('store_id', $request->getStoreIds())
            ->delete();

        return $isDeleted ?
            $this->responseMessage('Thành công') :
            $this->responseError('Thất bại');
    }

}
