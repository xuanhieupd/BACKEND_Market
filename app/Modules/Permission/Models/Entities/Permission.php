<?php

namespace App\Modules\Permission\Models\Entities;

use App\Base\AbstractModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permission extends AbstractModel
{

    protected $table = 'hnw_permission';
    protected $primaryKey = 'permission_id';
    public static $tableAlias = 'hnw_permission';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'group_id',
        'name_id',
        'title',
    );

    /**
     * Alias for `permission_id`
     *
     * @return int
     * @author xuanhieupd
     */
    public function getId()
    {
        return $this->getAttribute('permission_id');
    }

    /**
     * Name of permission
     *
     * @return string
     * @author xuanhieupd
     */
    public function getNameId()
    {
        return $this->getAttribute('name_id');
    }

    /**
     * Thông tin nhóm quyền
     *
     * @return BelongsTo|PermissionGroup
     * @author xuanhieupd
     */
    public function permissionGroup()
    {
        return $this->belongsTo(PermissionGroup::class, 'group_id', 'group_id');
    }
}
