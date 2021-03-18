<?php

namespace App\Modules\Role\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Role\Exceptions\RoleNotFoundException;
use App\Modules\Role\Models\Entities\Role;
use App\Modules\Role\Models\Repositories\Contracts\RoleInterface;
use Illuminate\Support\Collection;

class RoleRepository extends AbstractRepository implements RoleInterface
{

    /**
     * Lấy danh sách vai trò
     *
     * @param array $conditions
     * @param array $fetchOptions
     * @return Collection|Role
     * @author xuanhieupd
     */
    public function getRoles(array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->get();
    }

    /**
     * Lấy thông tin vai trò
     *
     * @param $roleId
     * @param array $conditions
     * @param array $fetchOptions
     * @return Role
     * @throws RoleNotFoundException
     * @author xuanhieupd
     */
    public function getRoleById($roleId, array $conditions = array(), array $fetchOptions = array())
    {
        $roleInfo = $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('role_id', $roleId)
            ->first();

        if (!$roleInfo) {
            throw new RoleNotFoundException();
        }

        return $roleInfo;
    }

    /**
     * @return Role
     */
    public function model()
    {
        return Role::class;
    }
}
