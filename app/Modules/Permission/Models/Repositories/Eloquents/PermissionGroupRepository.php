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
use App\Modules\Permission\Models\Entities\PermissionGroup;
use App\Modules\Permission\Models\Repositories\Contracts\PermissionGroupInterface;
use Illuminate\Support\Collection;

class PermissionGroupRepository extends AbstractRepository implements PermissionGroupInterface
{

    /**
     * Danh sách nhóm permission
     *
     * @param array $conditions
     * @param array $fetchOptions
     * @return Collection|PermissionGroup
     * @author xuanhieupd
     */
    public function getGroupPermissions(array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->get();
    }

    /**
     * @return PermissionGroup
     */
    public function model()
    {
        return PermissionGroup::class;
    }

}
