<?php

/**
 * Permission Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Permission
 * @copyright (c) 10.10.2020, HNW
 */

namespace App\Modules\Role\Modules\Permission\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Role\Modules\Permission\Models\Entities\Permission;
use App\Modules\Role\Modules\Permission\Models\Repositories\Contracts\PermissionInterface;
use Illuminate\Support\Collection;

class PermissionRepository extends AbstractRepository implements PermissionInterface
{

    /**
     * Lấy danh sách quyền mà người dùng được phép
     *
     * @param $userId
     * @return Collection|Permission
     * @author xuanhieupd
     */
    public function getUserPermissions($userId)
    {
        return $this->makeModel()
            ->whereHas('permissionRelation', function ($builder) {

            })
            ->get();

    }

    /**
     * @return Permission
     */
    public function model()
    {
        return Permission::class;
    }

}
