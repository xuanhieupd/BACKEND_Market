<?php

namespace App\Modules\Chat\ControllerAPI\Send;

use App\Base\AbstractController;
use App\Libraries\Chat\Models\Message;
use App\Modules\Chat\Helpers\ChatHelper;
use App\Modules\Chat\Resources\Message\MessageResource;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;

abstract class AbstractControllerSend extends AbstractController
{

    /**
     * @param Message $messageInfo
     * @return array
     */
    protected function loadResponse(Message $messageInfo)
    {
        $messageInfo = ChatHelper::loadMessageResource($messageInfo);

        return (new MessageResource($messageInfo))->toArray(request());
    }

    abstract protected function getAuthor();
}
