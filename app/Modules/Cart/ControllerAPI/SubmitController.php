<?php

/**
 * Submit Controller
 *
 * @author xuanhieupd
 * @package Cart
 * @copyright 04.10.2020, HNW
 */

namespace App\Modules\Cart\ControllerAPI;

use App\Base\AbstractController;
use App\GlobalConstants;
use App\Modules\Base\Helpers\CollectionHelper;
use App\Modules\Cart\Models\Repositories\Contracts\OrderCartInterface;
use App\Modules\Cart\Models\Repositories\Eloquents\OrderCartRepository;
use App\Modules\Cart\Requests\SubmitRequest;
use App\Modules\Order\Models\Entities\Item;
use App\Modules\Order\Models\Entities\Order;
use App\Modules\Order\Models\Repositories\Contracts\ItemInterface;
use App\Modules\Order\Models\Repositories\Contracts\OrderInterface;
use App\Modules\Order\Models\Repositories\Eloquents\ItemRepository;
use App\Modules\Order\Models\Repositories\Eloquents\OrderRepository;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use App\Modules\Product\Models\Services\StockService;
use App\Modules\Store\Models\Repositories\Eloquents\StoreRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SubmitController extends AbstractController
{

    /* @var OrderCartRepository */
    private $cartRepo;

    /**
     * @var StockService
     */
    private $stockService;

    /**
     * @var ItemRepository
     */
    private $itemRepo;

    /**
     * @var OrderRepository
     */
    protected $orderRepo;

    /**
     * @var StoreRepository
     */
    protected $storeRepo;

    /**
     * @var ProductRepository
     */
    protected $productRepo;

    /**
     * Constructor.
     *
     * @param $cartRepo
     * @author xuanhieupd
     */
    public function __construct(
        OrderCartInterface $cartRepo,
        StockService $stockService,
        ItemInterface $itemRepo,
        OrderInterface $orderRepo,
        ProductInterface $productRepo
    )
    {
        $this->cartRepo = $cartRepo;
        $this->stockService = $stockService;
        $this->itemRepo = $itemRepo;
        $this->orderRepo = $orderRepo;
        $this->productRepo = $productRepo;
    }

    /**
     * Danh sách sản phẩm có trong giỏ
     *
     * @param Request $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(SubmitRequest $request)
    {
        $cartRows = $this->cartRepo->getCarts(auth()->id())->get();
        if ($cartRows->isEmpty()) return $this->responseError('Giỏ đang trống');

        $productIds = CollectionHelper::pluckUnique($cartRows, 'product_id')->toArray();
        $products = $this->productRepo->whereIn('product_id', $productIds)->get();
        $products = $this->productRepo->bindPrice($products, auth()->id());

        DB::beginTransaction();
        try {
            $itemInserts = collect();

            foreach ($this->splitByStores($cartRows) as $storeId => $storeItems) {
                $itemProductIds = CollectionHelper::pluckUnique($storeItems, 'product_id')->toArray();
                $productItems = $products->whereIn('product_id', $itemProductIds);

                $orderInfo = $this->orderRepo->create(array(
                    'action_id' => Order::ACTION_NO_LAI,
                    'store_id' => $storeId,
                    'user_id' => auth()->id(),
                    'total_quantity' => $storeItems->sum('quantity'),
                    'total_price' => $storeItems->sum('total_price'),
                    'has_change_quantity' => GlobalConstants::STATUS_INACTIVE,
                    'has_change_price' => GlobalConstants::STATUS_INACTIVE,
                    'status' => $this->getOrderStatusByProducts($productItems),
                    'code' => null, 'total_receivable' => 0, 'total_expense' => 0,
                    'customer_id' => null, 'warehouse_id' => null, 'manager_id' => null,
                    'money_banking' => null, 'money_cash' => null,
                    'money_deposit' => null, 'money_fortune' => null,
                    'note' => ''
                ));

                foreach ($storeItems as $cartRow) {
                    $productInfo = $products->where('product_id', $cartRow->getAttribute('product_id'))->first();
                    if (!$productInfo) continue;

                    $itemInfo = new Item(array(
                        'order_id' => $orderInfo->getId(),
                        'product_id' => $cartRow->getAttribute('product_id'),
                        'variant_id' => $cartRow->getAttribute('variant_id'),
                        'quantity' => $cartRow->getAttribute('quantity'),
                        'price' => $cartRow->getAttribute('price'),
                        'created_at' => now(), 'updated_at' => now(),
                    ));

                    $itemInserts->push($itemInfo->toArray());
                }
            }

            $this->itemRepo->insert($itemInserts->toArray());

            DB::commit();
            return $this->responseMessage('Tạo toa thành công');
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            return $this->responseError('Lỗi khi tạo toa: ' . $e->getMessage());
        }
    }

    /**
     * Chia sản phẩm thành các cửa hàng riêng
     *
     * @param Collection $cartRows
     * @return Collection
     * @author xuanhieupd
     */
    protected function splitByStores(Collection $cartRows)
    {
        $results = collect();
        $storeIds = CollectionHelper::pluckUnique($cartRows, 'store_id');

        foreach ($storeIds as $storeId) {
            $datas = $cartRows->where('store_id', $storeId);
            $results->put($storeId, $datas);
        }

        return $results;
    }

    /**
     * @param $products
     * @return int
     */
    protected function getOrderStatusByProducts($products)
    {
        $statusId = Order::ORDER_PENDING;

        foreach ($products as $productInfo) {
            if (!is_null($productInfo->getMarketPrice())) continue;

            $statusId = Order::STATUS_QUOTATION_WAITING;
            break;
        }

        return $statusId;
    }
}


