<?php

namespace App\Modules\Product\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use App\Modules\Product\Requests\SearchRequest;
use App\Modules\Product\Resources\SearchResource;
use Illuminate\Support\Facades\Auth;

class SearchController extends AbstractController
{

    /**
     * @var ProductRepository
     */
    private $productRepo;

    /**
     * SearchController constructor.
     * @param ProductInterface $productRepo
     */
    public function __construct(ProductInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    /**
     * Tìm kiếm sản phẩm
     *
     * @param SearchRequest $request
     * @return SearchResource
     * @author xuanhieupd
     */
    public function actionIndex(SearchRequest $request)
    {
        $visitor = Auth::user();
        $products = $this->productRepo->getStoreSearch($visitor->getStoreId(), $request->getSearchQuery());

        return SearchResource::collection($products);
    }

}
