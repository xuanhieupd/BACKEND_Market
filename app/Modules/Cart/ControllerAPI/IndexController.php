<?php

/**
 * Index Controller
 *
 * @author xuanhieupd
 * @package Cart
 * @copyright 04.10.2020, HNW
 */

namespace App\Modules\Cart\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Base\Helpers\CollectionHelper;
use App\Modules\Cart\Models\Repositories\Contracts\OrderCartInterface;
use App\Modules\Cart\Models\Repositories\Eloquents\OrderCartRepository;
use App\Modules\Cart\Resources\CartResource;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use App\Modules\Product\Modules\Variant\Models\Repositories\Contracts\VariantInterface;
use App\Modules\Product\Modules\Variant\Models\Repositories\Eloquents\VariantRepository;
use App\Modules\Store\Models\Repositories\Contracts\StoreInterface;
use App\Modules\Store\Models\Repositories\Eloquents\StoreRepository;
use Illuminate\Http\Request;

class IndexController extends AbstractController
{

    /* @var OrderCartRepository */
    private $cartRepo;

    /**
     * @var StoreRepository
     */
    protected $storeRepo;

    /**
     * @var ProductRepository
     */
    protected $productRepo;

    /**
     * @var VariantRepository
     */
    protected $variantRepo;

    /**
     * Constructor.
     *
     * @param OrderCartInterface $cartRepo
     * @param StoreInterface $storeRepo
     * @param ProductInterface $productRepo
     * @param VariantInterface $variantRepo
     * @author xuanhieupd
     */
    public function __construct(OrderCartInterface $cartRepo, StoreInterface $storeRepo, ProductInterface $productRepo, VariantInterface $variantRepo)
    {
        $this->cartRepo = $cartRepo;
        $this->storeRepo = $storeRepo;
        $this->productRepo = $productRepo;
        $this->variantRepo = $variantRepo;
    }

    /**
     * Danh sách sản phẩm có trong giỏ
     *
     * @param Request $request
     * @return CartResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $cartRows = $this->cartRepo->getCarts(auth()->id())->get();

        $stores = $this->storeRepo->getStores()
            ->select(array('store_id', 'avatar', 'title'))
            ->whereIn('store_id', CollectionHelper::pluckUnique($cartRows, 'store_id')->toArray())
            ->get();

        $products = $this->productRepo->getProducts()
            ->select(array('product_id', 'store_id', 'sku', 'title'))
            ->whereIn('product_id', CollectionHelper::pluckUnique($cartRows, 'product_id')->toArray())
            ->get();

        $variants = $this->variantRepo->getVariants()
            ->select(array('variant_id', 'product_id', 'color_id', 'size_id'))
            ->with(array('variantColor', 'variantSize'))
            ->whereIn('variant_id', CollectionHelper::pluckUnique($cartRows, 'variant_id')->toArray())
            ->get();

        $resourceParams = array(
            'raw' => $cartRows,
            'stores' => $stores,
            'products' => $products,
            'variants' => $variants,
        );
        return new CartResource($resourceParams);
    }

}


