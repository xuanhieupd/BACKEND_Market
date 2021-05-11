<?php

namespace App\Modules\User\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\User\Http\Middleware\UserMiddleware;
use App\Modules\User\Resources\FullUserResource;
use Illuminate\Http\Request;

class UserController extends AbstractController
{

    /**
     * Constructor.
     *
     * @author xuanhieupd
     */
    public function __construct()
    {
        $this->middleware(array(UserMiddleware::class));
    }

    /**
     * @param Request $request
     * @return FullUserResource|mixed
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $userInfo = $request->input('user');

        return new FullUserResource($userInfo);
    }

}
