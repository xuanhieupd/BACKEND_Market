<?php

/**
 * Abstract Model
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Base
 * @copyright (c) 31.10.2020, HNW
 */

namespace App\Base;

use App\Base\Traits\OverrideTableName;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractModel extends Model
{

    use OverrideTableName;

    protected $connection = 'mysql';
    public $incrementing = true;
    public $timestamps = true;
    protected $filterable = array();

    public abstract function getId();

    public function getCreatedDate()
    {
        $createdAt = $this->getAttribute('created_at');
        return $createdAt ? $this->getAttribute('created_at')->toDateTimeString() : null;
    }

    public function getUpdatedDate()
    {
        $updatedAt = $this->getAttribute('updated_at');
        return $updatedAt ? $this->getAttribute('updated_at')->toDateTimeString() : null;
    }


}
