<?php


namespace App\Modules\Role\ControllerAPI;


use App\Base\AbstractController;
use App\Modules\Role\Exceptions\RoleNotFoundException;
use App\Modules\Role\Models\Repositories\Contracts\RoleInterface;
use App\Modules\Role\Models\Repositories\Eloquents\RoleRepository;
use App\Modules\Role\Resources\RolePermissionsResource;
use App\Modules\Role\Resources\RoleResource;
use Illuminate\Http\Request;

class RoleController extends AbstractController
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
        try {
            $roleId = $request->route('roleId');
            $roleInfo = $this->roleRepo->getRoleById($roleId);

            return new RolePermissionsResource($roleInfo);
        } catch (RoleNotFoundException $e) {
            return $this->responseError('Không tìm thấy vai trò');
        } catch (\Exception $e) {
            return $this->responseError('Lỗi không xác định');
        }
    }


}
