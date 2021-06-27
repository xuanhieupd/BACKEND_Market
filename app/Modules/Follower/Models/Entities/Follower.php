<?php

namespace App\Modules\Follower\Models\Entities;

use App\Base\AbstractModelRelation;

class Follower extends AbstractModelRelation
{

    protected $connection = 'box';
    protected $table = 'hnw_follower_user';
    protected $primaryKey = array(
        'store_id',
        'user_id',
    );

    public function getId()
    {
        return implode('_', array(
            $this->getAttribute('store_id'),
            $this->getAttribute('user_id'),
        ));
    }

    public function getDisplayId()
    {
        return $this->getAttribute('display_id');
    }

}
