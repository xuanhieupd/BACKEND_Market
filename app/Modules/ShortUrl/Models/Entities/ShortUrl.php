<?php

namespace App\Modules\ShortUrl\Models\Entities;

use App\Base\AbstractModel;

class ShortUrl extends AbstractModel
{

    protected $connection = 'box';
    protected $table = 'hnw_short_url';
    protected $primaryKey = 'short_id';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'code',
        'long_url',
    );

    /**
     * @return int
     */
    public function getId()
    {
        return $this->getAttribute('short_id');
    }

    /**
     * @return string
     */
    public function getLongUrl()
    {
        return $this->getAttribute('long_url');
    }


}
