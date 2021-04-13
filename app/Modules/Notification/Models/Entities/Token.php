<?php

namespace App\Modules\Notification\Models\Entities;

use App\Base\AbstractModel;

class Token extends AbstractModel
{

    protected $connection = 'box';
    protected $table = 'hnw_user_token_push';
    protected $primaryKey = 'token_id';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'store_id',
        'user_id',
        'type_id',
        'device_id',
        'token_value',
    );

    /**
     * @return int
     */
    public function getId()
    {
        return $this->getAttribute('token_id');
    }
}
