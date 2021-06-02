<?php

/**
 * Feeds Controller
 *
 * @author xuanhieupd
 * @package Feed
 * @copyright (c) 06.10.2020, HNW
 */

namespace App\Modules\Feed\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Base\Helpers\CollectionHelper;
use App\Modules\Feed\Models\Repositories\Contracts\FeedInterface;
use App\Modules\Feed\Models\Repositories\FeedRepository;
use App\Modules\Feed\Resources\FeedResource;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class FeedsController extends AbstractController
{

    /**
     * @var FeedRepository
     */
    protected $feedRepo;

    /**
     * @var ProductRepository
     */
    protected $productRepo;

    /**
     * Constructor.
     *
     * @param FeedInterface $feedRepo
     * @author xuanhieupd
     */
    public function __construct(FeedInterface $feedRepo, ProductInterface $productRepo)
    {
        $this->feedRepo = $feedRepo;
        $this->productRepo = $productRepo;
    }

    /**
     * Danh sách bảng tin
     *
     * @param Request $request
     * @return FeedsResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $currentPage = $request->get('page');

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $feeds = $this->feedRepo->getFeeds()
            ->filter($request->all())
            ->with(array('feedProductsPivot', 'feedAttachments', 'feedAuthor', 'feedLike', 'feedLike.likeUser', 'likeCounter'))
            ->withCount('feedComments')
            ->orderBy('feed_id', 'DESC')
            ->simplePaginate(20);

        $productsPivot = CollectionHelper::pluckUnique($feeds, 'feedProductsPivot')->flatten();

        $allProducts = $this->productRepo
            ->select(array('product_id', 'store_id', 'sku', 'title', 'whole_price', 'retail_price', 'import_price', 'collaborator_price', 'attachment_id'))
            ->with(array('productAttachment'))
            ->orderBy('product_id', 'DESC')
            ->whereNotNull('attachment_id')
            ->whereIn('product_id', CollectionHelper::pluckUnique($productsPivot, 'product_id')->toArray())
            ->public()
            ->get();

        $allProducts = $this->productRepo->bindPrice($allProducts, auth()->id());

        foreach ($feeds as $feedInfo) {
            $productIds = CollectionHelper::pluckUnique($feedInfo->feedProductsPivot, 'product_id')->toArray();
            $products = $allProducts->whereIn('product_id', $productIds);

            $feedInfo->setAttribute('products', $products);
        }

        return FeedResource::collection($feeds);
    }

}
