<?php

/**
 * Save Controller
 *
 * @author xuanhieupd
 * @package Feed
 * @copyright (c) 07.10.2020, HNW
 */

namespace App\Modules\Feed\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Attachment\Models\Repositories\Contracts\AttachmentInterface;
use App\Modules\Attachment\Models\Repositories\Eloquents\AttachmentRepository;
use App\Modules\Base\Helpers\CollectionHelper;
use App\Modules\Feed\Controllers\Requests\SaveRequest;
use App\Modules\Feed\Models\Entities\Feed;
use App\Modules\Feed\Models\Repositories\Contracts\FeedInterface;
use App\Modules\Feed\Models\Repositories\FeedRepository;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use App\Modules\Store\Models\Entities\Store;
use App\Modules\User\Models\Entities\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaveController extends AbstractController
{

    /**
     * @var AttachmentRepository
     */
    protected $attachmentRepo;

    /**
     * @var ProductRepository
     */
    protected $productRepo;

    /**
     * @var FeedRepository
     */
    protected $feedRepo;

    /**
     * Constructor.
     *
     * @param ProductInterface $productRepo
     * @param AttachmentInterface $attachmentRepo
     * @author xuanhieupd
     */
    public function __construct(
        ProductInterface $productRepo,
        AttachmentInterface $attachmentRepo,
        FeedInterface $feedRepo
    )
    {
        $this->attachmentRepo = $attachmentRepo;
        $this->productRepo = $productRepo;
        $this->feedRepo = $feedRepo;
    }

    /**
     * Thêm mới bảng tin
     *
     * @param SaveRequest $request
     * @return
     * @author xuanhieupd
     */
    public function actionIndex(SaveRequest $request)
    {
        try {
            DB::beginTransaction();
            $authorInfo = $this->getAuthor();
            $authorType = get_class($authorInfo);
            $maxProductInAFeed = config('feed.MAX_PRODUCT_IN_FEED');
            $maxImageInAFeed = config('feed.MAX_IMAGE_IN_FEED');
            $countProductInFeed = count($request->getProductIds());

            if ($this->_isLimit()) {
                return $this->responseError('Bạn đã đạt giới hạn đăng bản tin trong ngày hôm nay');
            }

            if ($countProductInFeed > $maxProductInAFeed) {
                return $this->responseError(strtr('Mỗi tin chỉ được phép thêm tối đa :maxProductInAFeed sản phẩm', array(':maxProductInAFeed' => $maxProductInAFeed)));
            }

            if (count($request->getAttachmentIds()) > $maxImageInAFeed) {
                return $this->responseError(strtr('Mỗi tin chỉ được phép thêm tối đa :maxImageInAFeed ảnh', array(':maxImageInAFeed' => $maxImageInAFeed)));
            }


            $attachments = $this->attachmentRepo
                ->select(array('attachment_id'))
                ->whereIn('attachment_id', $request->getAttachmentIds())
                ->where('user_id', auth()->id())
                ->get();


            $feedInfo = $this->feedRepo->create(array(
                'author_type' => get_class($authorInfo),
                'author_id' => $authorInfo->getId(),
                'title' => $request->get('title', ''),
                'description' => $request->get('description'),
            ));


            if ($authorType === Store::class && $countProductInFeed) {
                $products = $this->productRepo
                    ->select(array('product_id', 'category_id'))
                    ->with('productCategory')
                    ->whereIn('product_id', $request->getProductIds())
                    ->get();

                $productInFeeds = array();
                foreach ($products as $product) {
                    $category = $product->productCategory;
                    $productId = $product->product_id;
                    $categoryId = 0;
                    if ($category) {
                        $parent = $category->mapParent;
                        if ($category->level != 1 && $parent) {
                            $category = $parent;
                        }
                        $categoryId = $category->category_id;
                    }
                    $productInFeeds[] = array(
                        'product_id' => $productId,
                        'category_id' => $categoryId
                    );
                }
                $products->isEmpty() ? null :
                    $feedInfo->feedProducts()->sync($productInFeeds);
            }

            $attachments->isEmpty() ? null :
                $feedInfo->feedAttachments()->sync(CollectionHelper::pluckUnique($attachments, 'attachment_id')->toArray());

            $feedInfo->save();
            DB::commit();
            return $this->responseMessage('Thành công');
        } catch (\Exception $exception) {
            \Log::error($exception);
            DB::rollBack();
            return $this->responseError();
        }
    }

    /**
     * @return bool
     * @copyright (c) 3:09 PM, Julyboy
     * @author Julyboy <cntt0401.luuvietduc@gmail.com>
     */
    protected function _isLimit()
    {
        $authorInfo = $this->getAuthor();
        $authorType = get_class($authorInfo);
        $authorId = $authorInfo->getId();
        $maxTime = $authorType === User::class ? config('feed.MAX_USER_FEED_IN_DAY') : config('feed.MAX_STORE_FEED_IN_DAY');
        $totalFeed = Feed::query()
            ->selectRaw(DB::raw('COUNT(*) as total'))
            ->where('author_type', $authorType)
            ->where('author_id', $authorId)
            ->whereDate('created_at', date('Y-m-d'))
            ->first();
        return $totalFeed->total >= $maxTime;
    }

    /**
     * @return User
     * @author xuanhieupd
     */
    protected function getAuthor()
    {
        return Auth::user();
    }

}
