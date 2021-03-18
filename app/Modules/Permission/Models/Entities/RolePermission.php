<?php

namespace App\Modules\Permission\Models\Entities;

use App\Base\AbstractModelRelation;

class RolePermission extends AbstractModelRelation
{

    protected $table = 'hnw_acl_role_permission';
    protected $primaryKey = array('role_id', 'permission_id');
    public $timestamps = false;

    /**
     * Id
     *
     * @return string
     * @author xuanhieupd
     */
    public function getId()
    {
        return implode('_', array(
            $this->getAttribute('role_id'),
            $this->getAttribute('permission_id'),
        ));
    }
}
