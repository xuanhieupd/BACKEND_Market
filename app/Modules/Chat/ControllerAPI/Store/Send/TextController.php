<?php

namespace App\Modules\Chat\ControllerAPI\Store\Send;

use App\Modules\Chat\ControllerAPI\Send\TextController as BaseController;
use Illuminate\Support\Facades\Auth;

class TextController extends BaseController
{

    /**
     * @return Authenticatable
     */
    protected function getAuthor()
    {
        return Auth::user()->userStore;
    }

}
