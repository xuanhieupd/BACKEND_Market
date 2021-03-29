<?php

/**
 * Customer Group Model
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Group
 * @copyright (c) 14.11.2020, HNW
 */

namespace App\Modules\Customer\Modules\Group\Models\Entities;

use App\Base\AbstractModel;

class Group extends AbstractModel
{

    protected $connection = 'box';
    protected $table = 'hnw_customer_group';
    protected $primaryKey = 'group_id';
    public static $tableAlias = 'hnw_customer_group';

    /**
     * Alias for `group_id`
     *
     * @return int
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function getId()
    {
        return $this->getAttribute('group_id');
    }

    /**
     * Tiêu đề nhóm
     *
     * @return string
     * @author xuanhieupd
     */
    public function getTitle()
    {
        return $this->getAttribute('name');
    }

}
