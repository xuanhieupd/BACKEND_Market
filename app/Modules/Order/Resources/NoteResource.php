<?php

namespace App\Modules\Order\Resources;

use App\Base\AbstractResource;
use App\Modules\User\Resources\UserSimpleResource;
use Illuminate\Http\Request;

class NoteResource extends AbstractResource
{

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     * @author xuanhieupd
     */
    public function toArray($request)
    {
        $userInfo = $this->noteUser;

        return array(
            'note_id' => $this->getId(),
            'user' => new UserSimpleResource($userInfo),
            'description' => $this->getAttribute('description'),
            'created_date' => $this->getCreatedDate(),
            'updated_date' => $this->getUpdatedDate(),
        );
    }

}
