<?php

namespace App\Modules\Chat\ControllerAPI\Store;

use App\Base\AbstractController;
use App\Libraries\Chat\ConfigurationManager;
use App\Libraries\Chat\Facades\ChatFacade as Chat;
use App\Modules\Attachment\Models\Repositories\Contracts\AttachmentInterface;
use App\Modules\Attachment\Models\Repositories\Eloquents\AttachmentRepository;
use App\Modules\Chat\Models\Repositories\Contracts\ConversationInterface;
use App\Modules\Chat\Models\Repositories\ConversationRepository;
use App\Modules\Chat\Models\Services\TargetService;
use App\Modules\Chat\Requests\BulkRequest;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use App\Modules\User\Models\Repositories\Contracts\UserInterface;
use App\Modules\User\Models\Repositories\Eloquents\UserRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class BulkController extends AbstractController
{

    /**
     * @var BulkRequest
     */
    protected $request;

    /**
     * @var AttachmentRepository
     */
    protected $attachmentRepo;

    /**
     * @var ProductRepository
     */
    protected $productRepo;

    /**
     * @var UserRepository
     */
    protected $userRepo;

    /**
     * @var ConversationRepository
     */
    protected $conversationRepo;

    /**
     * Constructor.
     *
     * @param AttachmentInterface $attachmentRepo
     * @param ProductInterface $productRepo
     * @param UserInterface $userRepo
     * @author xuanhieupd
     */
    public function __construct(
        AttachmentInterface $attachmentRepo,
        ProductInterface $productRepo,
        UserInterface $userRepo,
        ConversationInterface $conversationRepo
    )
    {
        $this->attachmentRepo = $attachmentRepo;
        $this->productRepo = $productRepo;
        $this->userRepo = $userRepo;
        $this->conversationRepo = $conversationRepo;
    }

    /**
     * Gửi tin hàng loạt
     *
     * @param BulkRequest $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(BulkRequest $request)
    {
        $this->request = $request;
        $visitor = Auth::user();
        $storeInfo = $visitor->userStore;

        $targetUsers = (new TargetService())->getDataFromTarget($request->getTarget(), $visitor->getStoreId());

        $messageParams = array(
            'message' => $request->get('message', ''),
            'attachments' => $this->getAttachments(),
            'products' => $this->getProducts(),
        );

        foreach ($targetUsers as $userInfo) {
            $conversationInfo = $this->conversationRepo->betweenOrMakeConversation($storeInfo, $userInfo);
            if (!$conversationInfo) continue;

            Chat::message($messageParams)
                ->type(ConfigurationManager::CHAT_MESSAGE_TYPE_BULK)
                ->from($storeInfo)
                ->to($conversationInfo)
                ->send();
        }

        $successMessage = strtr('Đã gửi tới :count người dùng', array(':count' => $targetUsers->count()));
        return $this->responseMessage($successMessage);
    }

    /**
     * Danh sách sản phẩm
     *
     * @return Collection
     */
    protected function getProducts()
    {
        if (blank($this->request->getProductIds())) return collect();

        $visitor = Auth::user();
        return $this->productRepo->getStoreProducts($visitor->getStoreId())
            ->whereIn('product_id', $this->request->getProductIds())
            ->get();
    }

    /**
     * Danh sách file đính kèm
     *
     * @return Collection
     * @author xuanhieupd
     */
    protected function getAttachments()
    {
        if (blank($this->request->getAttachmentIds())) return collect();

        return $this->attachmentRepo->getAttachments()
            ->whereIn('attachment_id', $this->request->getAttachmentIds())
            ->get();
    }


}
