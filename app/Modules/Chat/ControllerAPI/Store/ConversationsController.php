<?php

namespace App\Modules\Chat\ControllerAPI\Store;

use App\Modules\Chat\ControllerAPI\ConversationsController as BaseController;
use App\Modules\Store\Models\Entities\Store;
use Illuminate\Support\Facades\Auth;

class ConversationsController extends BaseController
{

    /**
     * @return Store
     */
    protected function getAuthor()
    {
        return Auth::user()->userStore;
    }

}
