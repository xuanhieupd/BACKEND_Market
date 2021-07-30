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
use App\GlobalConstants;
use App\Modules\Follower\Models\Entities\Follower;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use App\Modules\Product\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

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

        $followStores = Follower::query()
//            ->whereIn('store_id', $storeIds)
            ->where('user_id', Auth::id())
            ->where('status_id', GlobalConstants::STATUS_ACTIVE)
            ->select('store_id')
            ->get()
            ->pluck('store_id')
            ->toArray();


        $products = $this->productRepo->getProducts()
            ->select(array('product_id', 'store_id', 'sku', 'title', 'whole_price', 'retail_price', 'import_price', 'collaborator_price', 'attachment_id'))
            ->filter($request->all())
            ->with(array('productAttachment'))
            ->public()
            ->where(function ($q) use ($followStores) {
                $q->where(function($q1){
                    $q1->where('assign_id', 0);
                })
                ->orWhere(function($q2) use ($followStores){
                    $q2->where('assign_id', '<>' ,0)
                    ->whereIn('store_id', $followStores);
                });
            })
            ->search($request->get('q'))
            ->sortBy($request->get('orderId'))
            ->simplePaginate(20);

        $products = $this->productRepo->bindPrice($products, auth()->id());

        return ProductResource::collection($products);
    }

}


