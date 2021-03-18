<?php

namespace App\Modules\Chat\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Base\Helpers\CollectionHelper;
use App\Modules\Chat\Models\Repositories\Contracts\MessageInterface;
use App\Modules\Chat\Models\Repositories\MessageRepository;
use App\Modules\Chat\Resources\Message\MessageResource;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class MessagesController extends AbstractController
{

    /**
     * @var MessageRepository
     */
    protected $messageRepo;

    /**
     * @var ProductRepository
     */
    protected $productRepo;

    /**
     * Constructor.
     *
     * @param MessageInterface $messageRepo
     * @author xuanhieupd
     */
    public function __construct(MessageInterface $messageRepo, ProductInterface $productRepo)
    {
        $this->messageRepo = $messageRepo;
        $this->productRepo = $productRepo;
    }

    /**
     * Danh sách tin nhắn
     *
     * @param Request $request
     * @return MessageResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $currentPage = $request->get('page');

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $messages = $this->messageRepo
            ->with(array(
                'messageAttachment',
                'messageAttachments',
                'messageProducts',
                'participation',
                'participation.messageable'
            ))
            ->where('conversation_id', $request->route('conversationId'))
            ->orderBy('created_at', 'DESC')
            ->simplePaginate(10);

        $messages = $this->bindProductPrice($messages);
        return MessageResource::collection($messages);
    }

    /**
     * Bind giá vào thông tin tin nhắn
     *
     * @param $messages
     * @return Collection
     * @author xuanhieupd
     */
    protected function bindProductPrice($messages)
    {
        $products = CollectionHelper::pluckUnique($messages, 'messageProducts')->flatten();

        $productBindeds = $this->productRepo->bindPrice($products, auth()->id());

        foreach ($messages as $messageInfo) {
            $messageProducts = $messageInfo->getAttribute('messageProducts');
            $productIds = CollectionHelper::pluckUnique($messageProducts, 'product_id');

            $products = $productBindeds->whereIn('product_id', $productIds);
            $messageInfo->setAttribute('messageProducts', $products);
        }

        return $messages;
    }

}
