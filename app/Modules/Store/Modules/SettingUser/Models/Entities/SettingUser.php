<?php

namespace App\Modules\Store\Modules\SettingUser\Models\Entities;

use App\Base\AbstractModelRelation;

class SettingUser extends AbstractModelRelation
{

    protected $table = 'hnw_user_store_setting';
    protected $primaryKey = array('store_id', 'user_id', 'name_id');
    public static $tableAlias = 'hnw_user_store_setting';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'store_id',
        'user_id',
        'display_id',
        'alias_name',
    );

    /**
     * Id
     *
     * @return string
     * @author xuanhieupd
     */
    public function getId()
    {
        return implode('_', array(
            $this->getAttribute('store_id'),
            $this->getAttribute('user_id'),
        ));
    }
}
