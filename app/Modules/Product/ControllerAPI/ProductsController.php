<?php

/**
 * Products Controller
 *
 * @author xuanhieupd
 * @package Product
 * @copyright 20.11.2020, HNW
 */

namespace App\Modules\Product\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use App\Modules\Product\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class ProductsController extends AbstractController
{

    /**
     * @var ProductRepository
     */
    private $productRepo;

    /**
     * Constructor.
     *
     * @param ProductInterface $productRepo
     * @author xuanhieupd
     */
    public function __construct(ProductInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    /**
     * Danh sách sản phẩm
     *
     * @param Request $request
     * @return ProductResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $currentPage = $request->get('page');

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $products = $this->productRepo->getProducts()
            ->select(array('product_id', 'store_id', 'sku', 'title', 'whole_price', 'retail_price', 'import_price', 'collaborator_price', 'attachment_id'))
            ->filter($request->all())
            ->with(array('productAttachment'))
            ->public()
            ->search($request->get('q'))
            ->sortBy($request->get('orderId'))
            ->simplePaginate(20);

        $products = $this->productRepo->bindPrice($products, auth()->id());

        return ProductResource::collection($products);
    }

}


