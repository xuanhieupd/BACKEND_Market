<?php

namespace App\Modules\Order\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Cart\Events\ECartSubmited;
use App\Modules\Cart\Models\Repositories\Contracts\OrderCartInterface;
use App\Modules\Cart\Models\Repositories\Eloquents\OrderCartRepository;
use App\Modules\Customer\Exceptions\CustomerNotFoundException;
use App\Modules\Customer\Models\Repositories\Contracts\CustomerInterface;
use App\Modules\Customer\Models\Repositories\Eloquents\CustomerRepository;
use App\Modules\Order\Models\Entities\Item;
use App\Modules\Order\Models\Entities\Order;
use App\Modules\Order\Models\Repositories\Contracts\ItemInterface;
use App\Modules\Order\Models\Repositories\Eloquents\ItemRepository;
use App\Modules\Order\Requests\AddRequest;
use App\Modules\Product\Models\Services\DAO\StockDAO;
use App\Modules\Product\Models\Services\StockService;
use App\Modules\Product\Modules\Variant\Models\Repositories\Contracts\VariantInterface;
use App\Modules\Product\Modules\Variant\Models\Repositories\Eloquents\VariantRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddController extends AbstractController
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
     * @var VariantRepository
     */
    private $variantRepo;

    /**
     * @var CustomerRepository
     */
    private $customerRepo;

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
        VariantInterface $variantRepo,
        CustomerInterface $customerRepo
    )
    {
        $this->cartRepo = $cartRepo;
        $this->stockService = $stockService;
        $this->itemRepo = $itemRepo;
        $this->variantRepo = $variantRepo;
        $this->customerRepo = $customerRepo;
    }

    /**
     * Thêm mới toa hàng
     *
     * @param AddRequest $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(AddRequest $request)
    {
        $variants = $this->variantRepo->getVariantsByIds($request->getVariantIds());
        if ($variants->isEmpty()) {
            return $this->responseError('Toa cần ít nhất có 1 sản phẩm');
        }

        DB::beginTransaction();
        try {
            $visitor = Auth::user();
            $customerInfo = $this->customerRepo->getStoreCustomerById($visitor->getStoreId(), $request->get('customer_id'));

            $orderInfo = new Order();
            $orderInfo->fill(array(
                'user_id' => $visitor->getId(),
                'store_id' => $visitor->getStoreId(),
                'customer_id' => $customerInfo->getId(),
                'total_quantity' => $request->getTotalQuantity(),
                'total_price' => $request->getTotalPrice(),
                'code' => implode('_', array(date('dmY'), Str::random(4))),
                'status' => Order::ORDER_PENDING,
            ));

            $orderInfo->save();

            $itemInserts = collect();
            foreach ($variants as $variantInfo) {
                $pickedInfo = $request->getDataInfo($variantInfo->getId());

                $itemInfo = new Item(array(
                    'order_id' => $orderInfo->getId(),
                    'product_id' => $variantInfo->getAttribute('product_id'),
                    'variant_id' => $variantInfo->getId(),
                    'quantity' => $pickedInfo['quantity'],
                    'price' => $pickedInfo['price'],
                    'created_at' => now(), 'updated_at' => now(),
                ));

                $itemInserts->push($itemInfo->toArray());
            }

            Item::insert($itemInserts->toArray());
            $this->stockHandler($orderInfo);

            event(new ECartSubmited($orderInfo));

            DB::commit();
            return $this->responseMessage('Tạo toa thành công', array('order_id' => $orderInfo->getId()));
        } catch (CustomerNotFoundException $e) {
            DB::rollBack();
            return $this->responseError('Không tìm thấy khách hàng');
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            return $this->responseError('Lỗi khi tạo toa: ' . $e->getMessage());
        }
    }

    /**
     * Handler stock
     *
     * @param Collection $items
     * @author xuanhieupd
     */
    protected function stockHandler(Order $orderInfo)
    {
        $visitor = Auth::user();
        $items = $this->itemRepo->getItemsByOrderId($orderInfo->getId());

        $handlers = collect();
        foreach ($items as $item) {
            $stockDao = new StockDAO($item, $item->getQuantity());
            $handlers->push($stockDao);
        }

        app(StockService::class)->handles($visitor, $handlers);
    }

}
