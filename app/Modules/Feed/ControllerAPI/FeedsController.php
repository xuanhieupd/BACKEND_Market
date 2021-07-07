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
use App\Modules\Category\Models\Repositories\Eloquents\CategoryRepository;
use App\Modules\Feed\Models\Entities\Feed;
use App\Modules\Feed\Models\Repositories\Contracts\FeedInterface;
use App\Modules\Feed\Models\Repositories\FeedRepository;
use App\Modules\Feed\Resources\FeedResource;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use App\Modules\Store\Models\Entities\Store;
use App\Modules\User\Models\Entities\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

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
     * @var CategoryRepository
     */
    protected $categoryRepo;

    /**
     * Constructor.
     *
     * @param FeedInterface $feedRepo
     * @author xuanhieupd
     */
    public function __construct(FeedInterface $feedRepo, ProductInterface $productRepo, CategoryRepository $categoryRepo)
    {
        $this->feedRepo = $feedRepo;
        $this->productRepo = $productRepo;
        $this->categoryRepo = $categoryRepo;
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
        $userLogin = Auth::user();

        $currentPage = $request->get('page');
        $type = $request->get('type', Feed::TYPE_USER);
        $authorType = $type == Feed::TYPE_USER ? User::class : Store::class;

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $conditions = array_merge($request->all(), array('author_type' => $authorType));

        if($type === Feed::TYPE_USER){
            $categoryIds = $this->categoryRepo->getFollowedCategories($userLogin)
                ->select(array('category_id'))
                ->pluck('category_id')
                ->toArray();
            $conditions['category_ids'] = $categoryIds;
        }

        $feeds = $this->feedRepo->getFeeds()
            ->filter($conditions)
            ->with(array('feedProductsPivot', 'feedAttachments', 'feedAuthor', 'feedLike', 'feedLike.likeUser', 'likeCounter'))
            ->withCount('feedComments')
            ->orderBy('feed_id', 'DESC')
            ->simplePaginate(20);

        $productsPivot = CollectionHelper::pluckUnique($feeds, 'feedProductsPivot')->flatten();

        $allProducts = $this->productRepo
            ->select(array('product_id', 'store_id', 'sku', 'title', 'whole_price', 'retail_price', 'import_price', 'collaborator_price', 'attachment_id', 'category_id'))
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
