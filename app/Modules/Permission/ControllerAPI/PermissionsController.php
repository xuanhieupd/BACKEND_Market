<?php

namespace App\Modules\Permission\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Permission\Models\Repositories\Contracts\PermissionGroupInterface;
use App\Modules\Permission\Models\Repositories\Eloquents\PermissionGroupRepository;
use App\Modules\Permission\Resources\PermissionGroupResource;
use Illuminate\Http\Request;

class PermissionsController extends AbstractController
{

    /**
     * @var PermissionGroupRepository
     */
    private $groupRepo;

    /**
     * Constructor.
     *
     * @param PermissionGroupInterface $permissionRepo
     * @author xuanhieupd
     */
    public function __construct(
        PermissionGroupInterface $groupRepo
    )
    {
        $this->groupRepo = $groupRepo;
    }

    /**
     * Danh sách permission
     *
     * @param Request $request
     * @return PermissionGroupResource
     */
    public function actionIndex(Request $request)
    {
        $groups = $this->groupRepo->getGroupPermissions(array(), array(
            'withs' => array('groupPermissions'),
        ));

        return PermissionGroupResource::collection($groups);
    }

}
