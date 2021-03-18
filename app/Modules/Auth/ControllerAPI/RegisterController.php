<?php

namespace App\Modules\Auth\ControllerAPI;

use App\Base\AbstractController;
use App\GlobalConstants;
use App\Modules\Auth\Requests\RegisterRequest;
use App\Modules\Auth\Resources\LoginResource;
use App\Modules\User\Models\Repositories\Contracts\ProfileInterface;
use App\Modules\User\Models\Repositories\Contracts\UserInterface;
use App\Modules\User\Models\Repositories\Eloquents\ProfileRepository;
use App\Modules\User\Models\Repositories\Eloquents\UserRepository;
use Illuminate\Support\Str;

class RegisterController extends AbstractController
{

    /**
     * @var UserRepository
     */
    protected $userRepo;

    /**
     * @var ProfileRepository
     */
    protected $profileRepo;

    /**
     * Constructor.
     *
     * @param UserInterface $userRepo
     * @author xuanhieupd
     */
    public function __construct(UserInterface $userRepo, ProfileInterface $profileRepo)
    {
        $this->userRepo = $userRepo;
        $this->profileRepo = $profileRepo;
    }

    /**
     * @param RegisterRequest $request
     * @return LoginResource
     * @author xuanhieupd
     */
    public function actionIndex(RegisterRequest $request)
    {
        $userInfo = $this->userRepo->create(array(
            'assign_id' => null,
            'fullname' => $request->getFullName(),
            'email' => $request->getPhoneNumber(),
            'password' => $request->getHashPassword(),
            'status' => GlobalConstants::STATUS_ACTIVE,
            'last_activity' => now(), 'register_date' => now(),
            'username' => '', 'avatar' => '',
            'order' => 0, 'store_id' => 0,
            'api_token' => Str::random(32),
        ));

        $this->profileRepo->create(array(
            'user_id' => $userInfo->getId(),
            'telephone' => $request->getPhoneNumber(),
            'dob_day' => 0, 'dob_month' => 0, 'dob_year' => 0, 'age' => 0
        ));

        return new LoginResource($userInfo);
    }

}
