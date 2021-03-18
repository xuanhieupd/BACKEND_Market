<?php

namespace App\Modules\Auth\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Resources\LoginResource;
use App\Modules\User\Exceptions\UserNotFoundException;
use App\Modules\User\Models\Entities\User;
use App\Modules\User\Models\Repositories\Contracts\UserInterface;
use App\Modules\User\Models\Repositories\Eloquents\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends AbstractController
{

    /**
     * @var UserRepository
     */
    private $userRepo;

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
     * Đăng nhập
     *
     * @param LoginRequest $request
     * @return LoginResource
     * @author xuanhieupd
     */
    public function actionIndex(LoginRequest $request)
    {
        try {
            $userInfo = $this->userRepo->getUserByCredential($request->getCredential());
            if (!Hash::check($request->getPassword(), $userInfo->getAttribute('password'))) {
                return $this->responseError('Mật khẩu không chính xác. Vui lòng thử lại');
            }

            $userInfo = $this->createToken($userInfo);
            return new LoginResource($userInfo);
        } catch (UserNotFoundException $e) {
            return $this->responseError('Tài khoản bạn đang đăng nhập không khớp với bất kì tài khoản nào');
        }
    }

    /**
     * @param User $userInfo
     * @return string
     */
    protected function createToken(User $userInfo)
    {
        $apiToken = $userInfo->getAttribute('api_token');

        if (blank($apiToken)) {
            $userInfo->setAttribute('api_token', Str::random(32));
            $userInfo->save();
        }

        return $userInfo;
    }


}
