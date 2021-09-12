<?php

namespace App\Modules\Feed\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Base\Helpers\CollectionHelper;
use App\Modules\Feed\Models\Repositories\Contracts\FeedInterface;
use App\Modules\Feed\Resources\FeedResource;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use Illuminate\Http\Request;

class FeedController extends AbstractController
{

    /**
     * @var FeedInterface
     */
    protected $feedRepo;

    /**
     * @var ProductInterface
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
     * Chi tiết bài tin
     *
     * @param Request $request
     * @return FeedResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $feedInfo = $this->feedRepo->getFeedById($request->route('feedId'))
            ->with(array('feedProductsPivot', 'feedAttachments', 'feedAuthor', 'feedLike', 'feedLike.likeUser', 'likeCounter'))
            ->first();

        if (!$feedInfo) return $this->responseError('Khoong Không tìm thấy bản tin');

        $allProducts = $this->productRepo
            ->select(array('product_id', 'store_id', 'sku', 'title', 'whole_price', 'retail_price', 'import_price', 'collaborator_price', 'attachment_id', 'category_id'))
            ->with(array('productAttachment'))
            ->orderBy('product_id', 'DESC')
            ->whereNotNull('attachment_id')
            ->whereIn('product_id', CollectionHelper::pluckUnique($feedInfo->feedProductsPivot, 'product_id')->toArray())
            ->public()
            ->get();

        $allProducts = $this->productRepo->bindPrice($allProducts, auth()->id());

        $feedInfo->setAttribute('products', $allProducts);

        return new FeedResource($feedInfo);
    }
}
