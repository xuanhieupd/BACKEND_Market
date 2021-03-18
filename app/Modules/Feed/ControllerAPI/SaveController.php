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
use App\Modules\Feed\Models\Repositories\Contracts\FeedInterface;
use App\Modules\Feed\Models\Repositories\FeedRepository;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use App\Modules\User\Models\Entities\User;
use Illuminate\Support\Facades\Auth;

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
        $authorInfo = $this->getAuthor();

        $attachments = $this->attachmentRepo
            ->select(array('attachment_id'))
            ->whereIn('attachment_id', $request->getAttachmentIds())
            ->where('user_id', auth()->id())
            ->get();

        $products = $this->productRepo
            ->select(array('product_id'))
            ->whereIn('product_id', $request->getProductIds())
            ->get();

        $feedInfo = $this->feedRepo->create(array(
            'author_type' => get_class($authorInfo),
            'author_id' => $authorInfo->getId(),
            'title' => $request->get('title', ''),
            'description' => $request->get('description'),
        ));

        $products->isEmpty() ? null :
            $feedInfo->feedProducts()->sync(CollectionHelper::pluckUnique($products, 'product_id')->toArray());

        $attachments->isEmpty() ? null :
            $feedInfo->feedAttachments()->sync(CollectionHelper::pluckUnique($attachments, 'attachment_id')->toArray());

        return $feedInfo->save() ?
            $this->responseMessage('Thành công') :
            $this->responseError('Thêm bảng tin thất bại');
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
