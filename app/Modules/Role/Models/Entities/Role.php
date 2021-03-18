<?php

namespace App\Modules\Role\Models\Entities;

use App\Base\AbstractModel;
use App\Modules\Role\Modules\Permission\Models\Entities\Permission;

class Role extends AbstractModel
{

    protected $table = 'hnw_acl_role';
    protected $primaryKey = 'role_id';
    public $timestamps = true;
    public static $tableAlias = 'hnw_acl_role';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'title',
    );

    /**
     * Alias for `role_id`
     *
     * @return int
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function getId()
    {
        return $this->getAttribute('role_id');
    }

    /**
     * Danh sách quyền
     *
     * @return BelongsToMany|Permission
     * @author xuanhieupd
     */
    public function rolePermissions()
    {
        return $this->belongsToMany(Permission::class, 'hnw_acl_role_permission', 'role_id', 'permission_id');
    }


}
