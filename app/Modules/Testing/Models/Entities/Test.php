<?php


namespace App\Modules\Testing\Models\Entities;

use App\Base\AbstractModel;
use Kalnoy\Nestedset\NodeTrait;


class Test extends AbstractModel
{
    use NodeTrait;

    protected $table = 'hnw_test';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = array(
        'name'
    );

    public function getId()
    {
        return $this->getAttribute('id');
    }
}
