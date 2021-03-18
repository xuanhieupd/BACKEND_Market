<?php

namespace App\Modules\Likeable\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Likeable\Resources\StoreResource;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use App\Modules\Store\Models\Repositories\Contracts\StoreInterface;
use App\Modules\Store\Models\Repositories\Eloquents\StoreRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class StoresController extends AbstractController
{

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
     * @param StoreInterface $storeRepo
     * @author xuanhieupd
     */
    public function __construct(StoreInterface $storeRepo, ProductInterface $productRepo)
    {
        $this->storeRepo = $storeRepo;
        $this->productRepo = $productRepo;
    }

    /**
     * Danh sách cửa hàng đang theo dõi
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

        $stores = $this->storeRepo->getFollowedStores($authorInfo)
            ->with(array('likeCounter'))
            ->simplePaginate(5);

        $globalProducts = collect();
        foreach ($stores as $storeInfo) {
            $products = $this->productRepo->getStoreProducts($storeInfo->getId())
                ->select(array('product_id', 'store_id', 'sku', 'title', 'whole_price', 'retail_price', 'import_price', 'collaborator_price', 'attachment_id'))
                ->with(array('productAttachment'))
                ->orderBy('product_id', 'DESC')
                ->whereNotNull('attachment_id')
                ->public()
                ->limit(6)
                ->get();

            $globalProducts = $globalProducts->merge($products);
        }

        $globalProducts = $this->productRepo->bindPrice($globalProducts, auth()->id());

        foreach ($stores as $storeInfo) {
            $products = $globalProducts->where('store_id', $storeInfo->getId());
            $storeInfo->setAttribute('datas', $products);
        }

        return StoreResource::collection($stores);
    }

}
