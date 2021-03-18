<?php

namespace App\Modules\Customer\Modules\Group\Resources;

use App\Base\AbstractResource;

class GroupResource extends AbstractResource
{

    public function toArray($request)
    {
        return array(
            'group_id' => $this->getId(),
            'title' => $this->getTitle(),
        );
    }

}
