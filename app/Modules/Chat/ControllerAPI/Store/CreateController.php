<?php

namespace App\Modules\Chat\ControllerAPI\Store;

use App\Modules\Chat\ControllerAPI\CreateController as BaseController;
use App\Modules\Chat\Exceptions\ParticipantException;
use App\Modules\Store\Models\Entities\Store;
use App\Modules\User\Models\Repositories\Contracts\UserInterface;
use App\Modules\User\Models\Repositories\Eloquents\UserRepository;
use Illuminate\Support\Facades\Auth;

class CreateController extends BaseController
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
     * @return Store
     */
    protected function from()
    {
        return Auth::user()->userStore;
    }

    /**
     *
     * @throws \Throwable
     */
    protected function to()
    {
        $userIdFromInput = $this->request->get('id');
        $userInfo = $this->userRepo->getUserById($userIdFromInput)->first();

        throw_if(!$userInfo, new ParticipantException());
        return $userInfo;
    }

}
