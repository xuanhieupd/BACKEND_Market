<?php

namespace App\Modules\User\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\User\Models\Repositories\Contracts\UserInterface;
use App\Modules\User\Models\Repositories\Eloquents\UserRepository;
use App\Modules\User\Resources\FullUserResource;
use Illuminate\Http\Request;

class UserController extends AbstractController
{

    /**
     * @var UserRepository
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
     * @return FullUserResource|mixed
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $userIdFromInput = $request->route('userId');
        $userInfo = $this->userRepo->getUserById($userIdFromInput)->first();

        if (!$userInfo) return $this->responseError('Không tìm thấy thông tin');

        return new FullUserResource($userInfo);
    }

}
