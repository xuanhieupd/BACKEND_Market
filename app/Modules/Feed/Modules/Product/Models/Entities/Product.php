<?php

namespace App\Modules\Feed\Modules\Product\Models\Entities;

use App\Base\AbstractModelRelation;

class Product extends AbstractModelRelation
{

    protected $table = 'hnw_feed_product';
    protected $primaryKey = array('feed_id', 'product_id');
    public static $tableAlias = 'hnw_feed_product';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'feed_id',
        'product_id',
        'category_id'
    );

    /**
     * Alias Id
     *
     * @return int
     * @author xuanhieupd
     */
    public function getId()
    {
        return implode('_', array($this->getAttribute('feed_id'), $this->getAttribute('product_id')));
    }
}
