<?php

namespace App\Modules\Product\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Attachment\Models\Repositories\Contracts\AttachmentInterface;
use App\Modules\Attachment\Models\Repositories\Eloquents\AttachmentRepository;
use App\Modules\Product\Models\Entities\Product;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use App\Modules\Product\Resources\ProductDetailResource;
use App\Modules\Product\Resources\ProductResource;
use App\Modules\Store\Modules\SettingUser\Constants\Constants;
use App\Modules\Store\Modules\SettingUser\Models\Repositories\Contracts\SettingUserInterface;
use App\Modules\Store\Modules\SettingUser\Models\Repositories\Eloquents\SettingUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductDetailController extends AbstractController
{

    /**
     * @var ProductRepository
     */
    private $productRepo;

    /**
     * @var AttachmentRepository
     */
    private $attachmentRepo;

    /**
     * @var SettingUserRepository
     */
    protected $settingUserRepo;

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
        SettingUserInterface $settingUserRepo
    )
    {
        $this->productRepo = $productRepo;
        $this->attachmentRepo = $attachmentRepo;
        $this->settingUserRepo = $settingUserRepo;
    }

    /**
     * Chi tiết sản phẩm
     *
     * @param Request $request
     * @return ProductResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $visitor = Auth::user();
        $productId = $request->route('productId');

        $productInfo = $this->productRepo->getProductById($productId)
            ->with(array('productAttachments', 'productVariants', 'productVariants.variantColor', 'productVariants.variantSize', 'productStore', 'likeCounter'))
            ->first();

        if (!$productInfo) return $this->responseError('Không tìm thấy thông tin sản phẩm');

        $visitor->seenable($productInfo);

        $productInfo = $this->bindDisplay($productInfo);
        return new ProductDetailResource($productInfo);
    }

    /**
     * @param Product $productInfo
     * @return Product
     */
    protected function bindDisplay(Product $productInfo)
    {
        $settingInfo = $this->settingUserRepo
            ->where('store_id', $productInfo->getAttribute('store_id'))
            ->where('user_id', auth()->id())
            ->first();


        if ($settingInfo) {
            $productInfo->setAttribute('canViewPrice', Constants::canViewPrice($settingInfo->getAttribute('display_id')));
            $productInfo->setAttribute('canViewQuantity', Constants::canViewQuantity($settingInfo->getAttribute('value')));

            return $productInfo;
        }

        $storeInfo = $productInfo->productStore;

        $productInfo->setAttribute('canViewPrice', $storeInfo->isPublicPrice());
        $productInfo->setAttribute('canViewQuantity', $storeInfo->isPublicQuantity());

        return $productInfo;

    }
}
