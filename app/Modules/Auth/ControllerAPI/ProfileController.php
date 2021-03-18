<?php

namespace App\Modules\Auth\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Auth\Resources\ProfileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends AbstractController
{

    /**
     * Thông tin cá nhân
     *
     * @param Request $request
     * @return ProfileResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $visitor = Auth::user();

        return new ProfileResource($visitor);
    }

}
