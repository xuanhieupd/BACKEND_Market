<?php

namespace App\Modules\Role\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Role\Models\Repositories\Contracts\RoleInterface;
use App\Modules\Role\Models\Repositories\Eloquents\RoleRepository;
use App\Modules\Role\Resources\RoleResource;
use Illuminate\Http\Request;

class RolesController extends AbstractController
{

    /**
     * @var RoleRepository
     */
    private $roleRepo;

    /**
     * Constructor.
     *
     * @param RoleInterface $roleRepo
     * @author xuanhieupd
     */
    public function __construct(RoleInterface $roleRepo)
    {
        $this->roleRepo = $roleRepo;
    }

    /**
     * Danh sách vai trò
     *
     * @param Request $request
     * @return RoleResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $roles = $this->roleRepo->getRoles();

        return RoleResource::collection($roles);
    }


}
