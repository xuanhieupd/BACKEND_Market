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
use App\Modules\Product\Jobs\CalculateProductQuantityJob;
use App\Modules\Product\Models\Entities\Product;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use App\Modules\Product\Models\Services\StockService;
use App\Modules\Store\Models\Repositories\Eloquents\StoreRepository;
use App\Modules\Store\Modules\SettingUser\Models\Repositories\Contracts\SettingUserInterface;
use App\Modules\Store\Modules\SettingUser\Models\Repositories\Eloquents\SettingUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
     * @var SettingUserRepository
     */
    protected $settingUserRepo;

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
        ProductInterface $productRepo,
        SettingUserInterface $settingUserRepo
    )
    {
        $this->cartRepo = $cartRepo;
        $this->stockService = $stockService;
        $this->itemRepo = $itemRepo;
        $this->orderRepo = $orderRepo;
        $this->productRepo = $productRepo;
        $this->settingUserRepo = $settingUserRepo;
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

        $products = $this->productRepo->whereIn('product_id', CollectionHelper::pluckUnique($cartRows, 'product_id')->toArray())->get();
        $products = $this->productRepo->bindPrice($products, auth()->id());

        $settings = $this->settingUserRepo->getSettings()
            ->whereIn('store_id', CollectionHelper::pluckUnique($cartRows, 'store_id'))
            ->where('user_id', auth()->id())
            ->get();

        DB::beginTransaction();
        try {
            $itemInserts = collect();
            $orders = collect();

            foreach ($cartRows->groupBy('store_id') as $storeId => $dataRows) {
                $settingInfo = $settings->where('store_id', $storeId)->first();
                $statusId = $this->getOrderStatusByProducts($products);

                $orderInfo = $this->orderRepo->create(array(
                    'store_id' => $storeId,
                    'customer_id' => $settingInfo ? $settingInfo->getAttribute('customer_id') : -1,
                    'customer_user_id' => auth()->id(), 'user_id' => auth()->id(), 'user_relation_id' => auth()->id(),
                    'user_manager_id' => null, 'user_warehouse_id' => null,
                    'action_id' => Order::ACTION_NO_LAI,
                    'bill_code' => implode('_', array(date('dmY'), Str::upper(Str::random(4)))),
                    'total_quantity' => $dataRows->sum('quantity'),
                    'total_price' => $dataRows->sum('total_price'),
                    'status' => $statusId,
                    'note' => '', 'expand_state' => 0,
                    'deposit' => 0, 'debt_info' => '',
                    'assign_id' => 0, 'relation_store_id' => 0, 'total_receivable' => 0, 'total_expense' => 0, 'money_cash' => 0, 'money_banking' => 0,
                ));


                $totalImportPrice = 0;
                foreach ($dataRows->groupBy('product_id') as $productId => $itemRows) {
                    $productInfo = $products->where('product_id', $productId)->first();
                    if (!$productInfo) continue;

                    $itemTotalImportPrice = $productInfo->getImportPrice() * $itemRows->sum('quantity');
                    $totalImportPrice += $itemTotalImportPrice;

                    $itemInfo = new Item(array(
                        'order_id' => $orderInfo->getId(),
                        'product_id' => $productId,
                        'total_quantity' => $itemRows->sum('quantity'),
                        'total_price' => $itemRows->sum('total_price'),
                        'total_import_price' => $itemTotalImportPrice,
                        'payload' => json_encode($this->buildPayload($itemRows, $productInfo)),
                    ));

                    $itemInserts->push($itemInfo->toArray());
                }

                $orderInfo->setAttribute('total_import_price', $totalImportPrice);
                $orderInfo->save();

                $orders->push($orderInfo);
            }

            $this->itemRepo->insert($itemInserts->toArray());
            $this->cartRepo->getCarts(auth()->id())->delete();

            foreach ($orders as $orderInfo) {
                if ($orderInfo->getAttribute('status') !== Order::ORDER_PENDING) continue;

                $jobInfo = new CalculateProductQuantityJob($orderInfo, CalculateProductQuantityJob::ACTION_ORDER_CART);
                app('queue')->connection('box')->pushOn('default', $jobInfo);
            }

            DB::commit();
            return $this->responseMessage('Tạo toa thành công');
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            return $this->responseError('Lỗi khi tạo toa: ' . $e->getMessage());
        }
    }

    /**
     * @param $items
     * @param Product $productInfo
     * @return array
     */
    protected function buildPayload($items, Product $productInfo)
    {
        $payload = array();

        foreach ($items as $itemInfo) {
            $payload[] = array(
                'variant_id' => $itemInfo->getAttribute('variant_id'),
                'quantity' => $itemInfo->getAttribute('quantity'),
                'price' => $itemInfo->getAttribute('price'),
                'import_price' => $productInfo->getImportPrice()
            );
        }

        return $payload;
    }


    /**
     * @param $products
     * @return int
     */
    protected function getOrderStatusByProducts($products)
    {
        $statusId = Order::ORDER_DRAFT;

        foreach ($products as $productInfo) {
            if (!is_null($productInfo->getMarketPrice())) continue;

            $statusId = Order::STATUS_QUOTATION_STORE_WAITING;
            break;
        }

        return $statusId;
    }
}


