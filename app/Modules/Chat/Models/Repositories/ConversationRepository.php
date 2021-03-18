<?php

namespace App\Modules\Chat\Models\Repositories;

use App\Base\AbstractRepository;
use App\Libraries\Chat\Models\Conversation;
use App\Modules\Chat\Models\Repositories\Contracts\ConversationInterface;

class ConversationRepository extends AbstractRepository implements ConversationInterface
{

    /**
     * @return string
     */
    public function model()
    {
        return Conversation::class;
    }
}
