<?php

/**
 * Delete Product Controller
 *
 * @author xuanhieupd
 * @package Order
 * @copyright 08.10.2020
 */

namespace App\Modules\Order\ControllerAPI\Change;

use App\Base\AbstractController;
use App\Modules\Order\Models\Entities\Item;
use App\Modules\Order\Models\Repositories\Contracts\ItemInterface;
use App\Modules\Order\Models\Repositories\Eloquents\ItemRepository;
use App\Modules\Order\Requests\Change\DeleteRequest;
use App\Modules\Product\Models\Services\DAO\StockDAO;
use App\Modules\Product\Models\Services\StockService;
use App\Modules\Product\Modules\StockTracking\Models\Entities\StockTracking;
use App\Modules\Product\Modules\StockTracking\Models\Repositories\Eloquents\StockTrackingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeleteController extends AbstractController
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
     * Xóa sản phẩm trong toa hàng
     *
     * @param DeleteRequest $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(DeleteRequest $request)
    {
        $orderInfo = $request->input('order');
        $productId = $request->get('product_id', -1);

        $items = $this->itemRepo->getItemsByOrderIdAndProductId($orderInfo->getId(), $productId);
        if ($items->isEmpty()) {
            return $this->responseError('Không có sản phẩm nào để xóa');
        }

        DB::beginTransaction();
        try {
            $this->logActivity($request);
            $this->stockHandlers($items);

            $this->itemRepo->deleteProduct($orderInfo->getId(), $productId);
            $orderInfo->tapEventItemUpdated();

            DB::commit();
            return $this->responseMessage('Xóa thành công');
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            return $this->responseError('Xóa thất bại ' . $e->getMessage());
        }
    }

    /**
     * Stock Handlers
     *
     * @param Collection|Item $items
     * @author xuanhieupd
     */
    public function stockHandlers($items)
    {
        $daoResults = collect();

        foreach ($items as $item) {
            $stockDao = new StockDAO($item, -$item->getQuantity());
            $daoResults->push($stockDao);
        }

        app(StockService::class)->handles(Auth::user(), $daoResults);
    }

    /**
     * Log Activity
     *
     * @param Request $request
     * @author xuanhieupd
     */
    public function logActivity(Request $request, Collection $items)
    {
        $orderInfo = $request->input('order');

        activity('order.item')
            ->causedBy(Auth::user())
            ->performedOn($orderInfo)
            ->withProperties($items->toArray())
            ->log('deleted.item');
    }


}
