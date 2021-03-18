<?php

namespace App\Modules\Permission\Models\Entities;

use App\Base\AbstractModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermissionGroup extends AbstractModel
{

    protected $table = 'hnw_acl_group_permission';
    protected $primaryKey = 'group_id';
    public $timestamps = false;
    public static $tableAlias = 'hnw_acl_group_permission';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'title',
    );

    /**
     * Alias for `group_id`
     *
     * @return int
     * @author xuanhieupd
     */
    public function getId()
    {
        return $this->getAttribute('group_id');
    }

    /**
     * Danh sách permission
     *
     * @return HasMany|Permission
     * @author xuanhieupd
     */
    public function groupPermissions()
    {
        return $this->hasMany(Permission::class, 'group_id', 'group_id')
            ->select(array(
                'permission_id',
                'group_id',
                'name_id',
                'title',
            ));
    }
}
