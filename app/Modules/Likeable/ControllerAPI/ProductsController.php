<?php

namespace App\Modules\Likeable\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use App\Modules\Likeable\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class ProductsController extends AbstractController
{

    /**
     * @var ProductRepository
     */
    protected $productRepo;

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
     * Danh sách sản phẩm đang yêu thích
     *
     * @param Request $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $currentPage = $request->get('page');

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $authorInfo = Auth::user();

        $products = $this->productRepo->getFollowedProducts($authorInfo)
            ->select(array('product_id', 'store_id', 'sku', 'title', 'whole_price', 'retail_price', 'import_price', 'collaborator_price', 'attachment_id'))
            ->with(array('productAttachment'))
            ->orderBy('product_id', 'DESC')
            ->whereNotNull('attachment_id')
            ->public()
            ->simplePaginate(20);

        $products = $this->productRepo->bindPrice($products, auth()->id());

        return ProductResource::collection($products);
    }

}
