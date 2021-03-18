<?php

/**
 * Change Price Controller
 *
 * @author xuanhieupd
 * @package Order
 * @copyright 05.10.2020
 */

namespace App\Modules\Order\ControllerAPI\Change;

use App\Base\AbstractController;
use App\Modules\Order\Models\Repositories\Contracts\ItemInterface;
use App\Modules\Order\Models\Repositories\Eloquents\ItemRepository;
use App\Modules\Order\Requests\Change\PriceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PriceController extends AbstractController
{

    /**
     * @var ItemRepository
     */
    private $itemRepo;

    /**
     * Constructor.
     *
     * @param ItemInterface $itemRepo
     * @author xuanhieupd
     */
    public function __construct(ItemInterface $itemRepo)
    {
        $this->itemRepo = $itemRepo;
    }

    /**
     * Thay đổi giá của sản phẩm trong toa
     *
     * @param PriceRequest $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(PriceRequest $request)
    {
        $orderInfo = $request->input('order');
        $productId = $request->get('product_id');
        $price = $request->get('price');

        DB::beginTransaction();
        try {
            $this->_log($request);

            $this->itemRepo->updatePrice($orderInfo->getId(), $productId, $price);

            $orderInfo->tapEventItemUpdated();

            DB::commit();
            return $this->responseMessage('Cập nhật giá thành công');
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            return $this->responseError('Cập nhật giá thất bại ' . $e->getMessage());
        }
    }

    /**
     * Log Activity
     *
     * @param Request $request
     * @author xuanhieupd
     */
    public function logActivity(Request $request)
    {
        $orderInfo = $request->input('order');
        $productId = $request->get('product_id');
        $price = $request->get('price');

        $item = $this->itemRepo->getItemByOrderIdAndProductId($orderInfo->getId(), $productId);
        if (!$item) {
            return;
        }

        activity('order.item')
            ->causedBy(Auth::user())
            ->performedOn($orderInfo)
            ->withProperties(array(
                'oldPrice' => $item->getAttribute('price'),
                'newPrice' => $price,
            ))
            ->log('updated.price');
    }

}
