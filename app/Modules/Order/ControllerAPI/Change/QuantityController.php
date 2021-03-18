<?php

/**
 * Change Quantity Controller
 *
 * @author xuanhieupd
 * @package Order
 * @copyright 05.10.2020
 */

namespace App\Modules\Order\ControllerAPI\Change;

use App\Base\AbstractController;
use App\Modules\Order\Models\Repositories\Contracts\ItemInterface;
use App\Modules\Order\Models\Repositories\Eloquents\ItemRepository;
use App\Modules\Order\Requests\Change\QuantityRequest;
use App\Modules\Product\Models\Services\DAO\StockDAO;
use App\Modules\Product\Models\Services\StockService;
use App\Modules\User\Models\Entities\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuantityController extends AbstractController
{

    /**
     * @var ItemRepository
     */
    private $itemRepo;

    /**
     * Constructor.
     *
     * @param $itemRepo
     * @author xuanhieupd
     */
    public function __construct(ItemInterface $itemRepo)
    {
        $this->itemRepo = $itemRepo;
    }

    /**
     * Thay đổi số lượng trong toa
     *
     * @param QuantityRequest $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(QuantityRequest $request)
    {
        $visitor = Auth::user();
        $orderInfo = $request->input('order');

        DB::beginTransaction();
        try {
            $items = $this->itemRepo->getItemsByOrderIdAndVariantIds($orderInfo->getId(), $request->getVariantIds());

            $logOldAttributes = collect();
            $logNewAttributes = collect();

            foreach ($items as $item) {
                if (!$request->hasVariant($item->getVariantId())) {
                    continue;
                }

                $logOldAttributes->push($item);

                $item->setAttribute('quantity', $request->getQuantity($item->getVariantId()));
                $item->save();

                $logNewAttributes->push($item);
            }

            $this->logActivity($request, $logOldAttributes, $logNewAttributes);
            $this->stockHandlers($visitor, $items, $request);
            $orderInfo->tapEventItemUpdated();

            DB::commit();
            return $this->responseMessage('Thay đổi số lượng thành công');
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            return $this->responseError('Thay đổi số lượng thất bại');
        }
    }

    /**
     * Xử lý log tồn
     *
     * @param User $userInfo
     * @param Collection $item
     * @author xuanhieupd
     */
    protected function stockHandlers(User $userInfo, Collection $items, QuantityRequest $request)
    {
        $stockHandlers = collect();

        foreach ($items as $item) {
            $stockLeft = $item->getQuantity() - $request->getQuantity($item->getVariantId());
            $stockHandlers->push(new StockDAO($item, $stockLeft));
        }

        app(StockService::class)->handles($userInfo, $stockHandlers);
    }

    /**
     * Log Activity
     *
     * @param Request $request
     * @author xuanhieupd
     */
    public function logActivity(Request $request, Collection $old, Collection $new)
    {
        $orderInfo = $request->input('order');

        activity('order.item')
            ->causedBy(Auth::user())
            ->performedOn($orderInfo)
            ->withProperties(array('attributes' => $new, 'old' => $old))
            ->log('updated.quantity');
    }

}
