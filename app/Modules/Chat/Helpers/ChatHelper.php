<?php

namespace App\Modules\Chat\Helpers;

use App\Libraries\Chat\Models\Message;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;

class ChatHelper
{

    /**
     * @param Message $messageInfo
     * @return Message
     */
    public static function loadMessageResource(Message $messageInfo)
    {
        $messageInfo->load(array(
            'messageAttachment',
            'messageAttachments',
            'messageProducts',
        ));

        $messageProducts = $messageInfo->messageProducts;
        $products = app(ProductInterface::class)->bindPrice($messageProducts, auth()->id());

        $messageInfo->setAttribute('messageProducts', $products);

        return $messageInfo;
    }

}
