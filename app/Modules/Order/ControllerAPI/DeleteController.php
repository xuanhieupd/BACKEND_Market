<?php

/**
 * Delete Controller
 *
 * @author xuanhieupd
 * @package Order
 * @copyright 05.10.2020
 */

namespace App\Modules\Order\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Order\Models\Entities\Item;
use App\Modules\Order\Models\Repositories\Contracts\ItemInterface;
use App\Modules\Order\Models\Repositories\Eloquents\ItemRepository;
use App\Modules\Product\Models\Services\DAO\StockDAO;
use App\Modules\Product\Models\Services\StockService;
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
     * Xóa toa hàng
     *
     * @param Request $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $orderInfo = $request->input('order');

        DB::beginTransaction();
        try {
            $items = $this->itemRepo->getItemsByOrderId($orderInfo->getId());
            $this->stockHandlers($items);
            $this->logActivity($request);

            DB::commit();
            return $this->responseMessage('Xóa thành công');
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            return $this->responseError('Xóa thất bại');
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
    public function logActivity(Request $request)
    {
        $orderInfo = $request->input('order');

        activity('order')
            ->causedBy(Auth::user())
            ->performedOn($orderInfo)
            ->log('deleted');
    }
}
