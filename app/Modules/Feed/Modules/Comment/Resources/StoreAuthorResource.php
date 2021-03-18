<?php

namespace App\Modules\Feed\Modules\Comment\Resources;

use Illuminate\Support\Facades\Auth;

class StoreAuthorResource extends AuthorResource
{

    public function toArray($request)
    {
        $baseDatas = parent::toArray($request);

        $baseDatas['is_following'] = $this->liked(Auth::user());

        return $baseDatas;
    }

}
