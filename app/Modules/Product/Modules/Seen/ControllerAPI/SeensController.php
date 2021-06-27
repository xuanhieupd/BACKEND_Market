<?php

namespace App\Modules\Product\Modules\Seen\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Base\Helpers\CollectionHelper;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use App\Modules\Product\Modules\Seen\Models\Repositories\Contracts\SeenInterface;
use App\Modules\Product\Modules\Seen\Models\Repositories\Eloquents\SeenRepository;
use App\Modules\Product\Modules\Seen\Resources\SeenResource;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class SeensController extends AbstractController
{

    /**
     * @var SeenRepository
     */
    protected $seenRepo;

    /**
     * @var ProductRepository
     */
    protected $productRepo;

    /**
     * Constructor.
     *
     * @param SeenInterface $seenRepo
     * @author xuanhieupd
     */
    public function __construct(SeenInterface $seenRepo, ProductInterface $productRepo)
    {
        $this->seenRepo = $seenRepo;
        $this->productRepo = $productRepo;
    }

    /**
     * Danh sách đã xem
     *
     * @param Request $request
     * @return SeenResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $currentPage = $request->get('page');

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $seens = $this->seenRepo->getProductsByUserId(auth()->id())
            ->whereHas('seenProduct', function ($builder) use ($request) {
                $builder
                    ->filter($request->all())
                    ->public()
                    ->sortBy($request->get('orderId'));

            })
            ->orderBy('updated_at', 'DESC')
            ->simplePaginate(20);

        $products = $this->productRepo
            ->select(array('product_id', 'store_id', 'sku', 'title', 'whole_price', 'retail_price', 'import_price', 'collaborator_price', 'attachment_id'))
            ->with(array('productAttachment'))
            ->orderBy('product_id', 'DESC')
            ->whereNotNull('attachment_id')
            ->whereIn('product_id', CollectionHelper::pluckUnique($seens, 'product_id')->toArray())
            ->public()
            ->get();

        $products = $this->productRepo->bindPrice($products, auth()->id());

        foreach ($seens as $seenIndex => $seenInfo) {
            $productInfo = $products->where('product_id', $seenInfo->getAttribute('product_id'))->first();
            if (!$productInfo) {
                $seens->forget($seenIndex);
                continue;
            }

            $seenInfo->setAttribute('product', $productInfo);
        }

        return SeenResource::collection($seens);
    }


}
