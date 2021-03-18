<?php

namespace App\Modules\Chat\ControllerAPI\Send;

use App\Libraries\Chat\ConfigurationManager;
use App\Modules\Chat\Requests\ProductRequest;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use App\Libraries\Chat\Facades\ChatFacade as Chat;

class ProductController extends AbstractControllerSend
{

    /**
     * @var ProductRepository
     */
    protected $productRepo;

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
     * Gửi tin nhắn sản phẩm
     *
     * @param ProductRequest $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(ProductRequest $request)
    {
        $conversationInfo = $request->input('conversation');

        $productIds = $request->get('product_ids', array());
        $products = $this->productRepo->getProductsByIds($productIds);

        $messageParams = array(
            'message' => $request->get('message', ''),
            'products' => $products,
        );

        $messageInfo = Chat::message($messageParams)
            ->type(ConfigurationManager::CHAT_MESSAGE_TYPE_PRODUCT)
            ->from($this->getAuthor())
            ->to($conversationInfo)
            ->send();

        return $this->responseMessage('Thành công', $this->loadResponse($messageInfo));
    }

    /**
     * @return Authenticatable
     */
    protected function getAuthor()
    {
        return Auth::user();
    }

}
