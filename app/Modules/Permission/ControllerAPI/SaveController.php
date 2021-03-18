<?php

namespace App\Modules\Permission\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Permission\Models\Entities\RolePermission;
use App\Modules\Permission\Models\Repositories\Contracts\PermissionInterface;
use App\Modules\Permission\Models\Repositories\Eloquents\PermissionRepository;
use App\Modules\Permission\Requests\SaveRequest;
use App\Modules\Role\Exceptions\RoleNotFoundException;
use App\Modules\Role\Models\Repositories\Contracts\RoleInterface;
use App\Modules\Role\Models\Repositories\Eloquents\RoleRepository;
use Illuminate\Support\Facades\DB;

class SaveController extends AbstractController
{

    /**
     * @var RoleRepository
     */
    private $roleRepo;

    /**
     * @var PermissionRepository
     */
    private $permissionRepo;

    /**
     * Constructor.
     *
     * @param RoleInterface $roleRepo
     * @author xuanhieupd
     */
    public function __construct(
        RoleInterface $roleRepo,
        PermissionInterface $permissionRepo
    )
    {
        $this->roleRepo = $roleRepo;
        $this->permissionRepo = $permissionRepo;
    }

    /**
     * Lưu phân quyền
     *
     * @param SaveRequest $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(SaveRequest $request)
    {
        $roleId = $request->get('role_id');
        try {
            $roleInfo = $this->roleRepo->getRoleById($roleId);
            $permissions = $this->permissionRepo->getPermissionsByIds($request->getPermissionIds(), array(), array(
                'fields' => array('permission_id')
            ));

            DB::table((new RolePermission())->getTable())
                ->where('role_id', $roleInfo->getId())
                ->delete();

            $dataInserts = collect();
            foreach ($permissions as $permissionInfo) {
                $dataInserts[] = array('role_id' => $roleInfo->getId(), 'permission_id' => $permissionInfo->getId());
            }

            RolePermission::insert($dataInserts->toArray());

            return $this->responseMessage('Lưu thành công');
        } catch (RoleNotFoundException $e) {
            return $this->responseError('Không tìm thấy vai trò');
        } catch (\Exception $e) {
            return $this->responseError('Lưu thất bại');
        }
    }

}
