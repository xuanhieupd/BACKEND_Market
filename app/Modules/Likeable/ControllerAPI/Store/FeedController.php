<?php

namespace App\Modules\Likeable\ControllerAPI\Store;

use App\Modules\Likeable\ControllerAPI\FeedController as BaseFeedController;
use Illuminate\Support\Facades\Auth;
use App\Modules\User\Models\Entities\Store;

class FeedController extends BaseFeedController
{

    /**
     * @return Store
     */
    protected function getAuthor()
    {
        $userInfo = Auth::user();

        return $userInfo->userStore;
    }

}
