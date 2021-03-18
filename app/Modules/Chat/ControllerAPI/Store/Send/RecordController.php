<?php

namespace App\Modules\Chat\ControllerAPI\Store\Send;

use App\Modules\Chat\ControllerAPI\Send\RecordController as BaseController;
use App\Modules\Store\Models\Entities\Store;
use Illuminate\Support\Facades\Auth;

class RecordController extends BaseController
{

    /**
     * @return Store
     */
    protected function getAuthor()
    {
        return Auth::user()->userStore;
    }

}
