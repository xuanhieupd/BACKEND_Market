<?php

namespace App\Modules\Role\Modules\Permission\Models\Entities;

use App\Base\AbstractModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permission extends AbstractModel
{

    protected $table = 'hnw_acl_permission';
    protected $primaryKey = 'permission_id';
    public $timestamps = true;
    public static $tableAlias = 'hnw_acl_permission';

    protected $fillable = array();

    /**
     * Alias for `permission_id`
     *
     * @return int
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function getId()
    {
        return $this->getAttribute('permission_id');
    }

    /**
     * Danh sách Relations
     *
     * @return BelongsTo|RolePermission
     * @author xuanhieupd
     */
    public function permissionRelation()
    {
        return $this->belongsTo(RolePermission::class, 'permission_id', 'permission_id');
    }

}
