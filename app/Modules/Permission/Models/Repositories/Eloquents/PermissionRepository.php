<?php

/**
 * Permission Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Permission
 * @copyright (c) 21.11.2020, HNW
 */

namespace App\Modules\Permission\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Permission\Models\Entities\Permission;
use App\Modules\Permission\Models\Repositories\Contracts\PermissionInterface;
use Illuminate\Support\Collection;

class PermissionRepository extends AbstractRepository implements PermissionInterface
{

    /**
     * Danh sách permission
     *
     * @param array $conditions
     * @param array $fetchOptions
     * @return Collection|Permission
     * @author xuanhieupd
     */
    public function getPermissions(array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->get();
    }

    /**
     * Lấy danh sách permission theo Ids
     *
     * @param $permissionIds
     * @param array $conditions
     * @param array $fetchOptions
     * @return Collection|Permission
     * @author xuanhieupd
     */
    public function getPermissionsByIds($permissionIds, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->whereIn('permission_id', $permissionIds)
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
