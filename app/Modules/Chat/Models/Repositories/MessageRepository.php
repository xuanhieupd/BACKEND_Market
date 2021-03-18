<?php

namespace App\Modules\Chat\Models\Repositories;

use App\Base\AbstractRepository;
use App\Libraries\Chat\Models\Message;
use App\Modules\Chat\Models\Repositories\Contracts\MessageInterface;

class MessageRepository extends AbstractRepository implements MessageInterface
{

    /**
     * @return string
     */
    public function model()
    {
        return Message::class;
    }
}
