<?php

/**
 * Detail Controller
 *
 * @author xuanhieupd
 * @package Order
 * @copyright 06.10.2020, HNW
 */

namespace App\Modules\Order\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Base\Helpers\CollectionHelper;
use App\Modules\Order\DAO\ProductPicked;
use App\Modules\Order\Models\Entities\Order;
use App\Modules\Order\Resources\OrderDetailResource;
use App\Modules\Product\Models\Entities\Product;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use App\Modules\Product\Modules\Variant\Models\Repositories\Contracts\VariantInterface;
use App\Modules\Product\Modules\Variant\Models\Repositories\Eloquents\VariantRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DetailController extends AbstractController
{

    /**
     * @var ProductRepository
     */
    private $productRepo;

    /**
     * @var VariantRepository
     */
    private $variantRepo;

    /**
     * Constructor.
     *
     * @param ProductInterface $productRepo
     * @return void
     * @author xuanhieupd
     */
    public function __construct(
        ProductInterface $productRepo,
        VariantInterface $variantRepo
    )
    {
        $this->productRepo = $productRepo;
        $this->variantRepo = $variantRepo;
    }

    /**
     * Chi tiết toa hàng
     *
     * @param Request $request
     * @return OrderDetailResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $orderInfo = $request->input('order');
        $orderInfo->load(array(
            'orderUser',
            'orderStore',
            'orderCustomer',
            'orderProducts',
            'orderProducts.itemProduct',
        ));

        $resourceParams = array(
            'order' => $orderInfo,
            'products' => $this->getProducts($orderInfo),
            'pickeds' => $this->getVariants($orderInfo),
        );
        return new OrderDetailResource($resourceParams);
    }

    /**
     * Lấy danh sách sản phẩm trong toa hàng
     *
     * @param Order $orderInfo
     * @return Collection|Product
     * @author xuanhieupd
     */
    protected function getProducts(Order $orderInfo)
    {
        $orderProducts = $orderInfo->orderProducts;
        $productIds = $orderProducts ? $orderProducts->pluck('product_id')->toArray() : array();

        $fetchOptions = array('withs' => array());
        return $this->productRepo->getProductsByIds($productIds, array(), $fetchOptions);
    }

    /**
     * Lấy danh sách mã trong toa hàng
     *
     * @param Order $orderInfo
     * @return Collection|Product
     * @author xuanhieupd
     */
    protected function getVariants(Order $orderInfo)
    {
        $variantResults = collect();

        $orderProducts = $orderInfo->orderProducts;
        $payloads = collect();

        foreach ($orderProducts as $orderProduct) {
            $payloads = $payloads->merge(collect($orderProduct->getPayload()));
        }

        $variantIds = CollectionHelper::pluckUnique($payloads, 'variant_id');
        $fetchOptions = array('withs' => array('variantColor', 'variantSize'));
        $variants = $this->variantRepo->getVariantsByIds($variantIds, array(), $fetchOptions);

        foreach ($variants as $variantInfo) {
            $pickedInfo = $payloads->where('variant_id', $variantInfo->getId())->first();

            if (!$pickedInfo) continue;

            $productPicked = new ProductPicked();
            $productPicked->setVariant($variantInfo);
            $productPicked->setQuantity($pickedInfo['quantity']);
            $productPicked->setPrice($pickedInfo['price']);

            $variantResults->push($productPicked);
        }

        return $variantResults;
    }

}
