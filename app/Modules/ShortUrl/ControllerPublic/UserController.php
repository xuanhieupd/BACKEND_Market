<?php

namespace App\Modules\ShortUrl\ControllerPublic;

use App\Base\AbstractController;
use App\Modules\ShortUrl\Exceptions\SpecificException;
use App\Modules\ShortUrl\Models\Services\ShortUrlService;
use App\Modules\User\Models\Repositories\Contracts\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class UserController extends AbstractController
{

    /**
     * @var UserInterface
     */
    protected $userRepo;

    /**
     * Constructor.
     *
     * @param UserInterface $userRepo
     * @author xuanhieupd
     */
    public function __construct(UserInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * @param Request $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        try {
            $data = ShortUrlService::specific($request->route('modelId'));
            $userInfo = $this->userRepo->getUserById($data['modelId'])->first();
            if (!$userInfo) return abort(404);

            $fullUrl = ShortUrlService::toUrl($data['appId'], strtr('user/:userId', array(':userId' => $userInfo->getId())));
            if (blank($fullUrl)) return abort(404);

            return Redirect::to($fullUrl);
        } catch (SpecificException $e) {
            return abort(404);
        }
    }

}
