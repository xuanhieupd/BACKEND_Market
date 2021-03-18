<?php


namespace App\Modules\Feed\ControllerAPI\Store;

use App\Modules\Feed\ControllerAPI\SaveController as BaseSaveController;
use Illuminate\Support\Facades\Auth;

class SaveController extends BaseSaveController
{

    /**
     * @return Store
     * @author xuanhieupd
     */
    protected function getAuthor()
    {
        $userInfo = Auth::user();

        return $userInfo->userStore;
    }


}
