<?php

namespace App\Modules\Permission\Requests;

use App\Base\AbstractRequest;

class SaveRequest extends AbstractRequest
{

    /**
     * Rules
     *
     * @return array
     * @author xuanhieupd
     */
    public function rules()
    {
        return array(
            'role_id' => 'required|numeric',
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'required|numeric',
        );
    }

    /**
     * Danh sách quyền được phép
     *
     * @return array
     * @author xuanhieupd
     */
    public function getPermissionIds()
    {
        return $this->get('permission_ids');
    }

}
