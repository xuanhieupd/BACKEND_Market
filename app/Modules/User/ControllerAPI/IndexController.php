<?php

namespace App\Modules\User\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\User\Models\Repositories\Contracts\UserInterface;
use App\Modules\User\Models\Repositories\Eloquents\UserRepository;
use App\Modules\User\Resources\UserSimpleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends AbstractController
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
     * Danh sách người dùng có trong cửa hàng
     *
     * @param Request $request
     * @return UserSimpleResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $visitor = Auth::user();

        $fetchOptions = array('fields' => array('user_id', 'fullname'));
        $users = $this->userRepo->getStoreUsers($visitor->getStoreId(), array(), $fetchOptions);

        return UserSimpleResource::collection($users);
    }

}
