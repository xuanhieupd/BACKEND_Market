<?php

/**
 * Product Attachment Model
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Attachment
 * @copyright (c) 07.10.2020, HNW
 */

namespace App\Modules\Product\Modules\Attachment\Models\Entities;

use App\Base\AbstractModelRelation;

class Attachment extends AbstractModelRelation
{

    protected $connection = 'box';
    protected $table = 'hnw_product_image';
    protected $primaryKey = array('attachment_id', 'product_id');
    public static $tableAlias = 'hnw_product_image';

    protected $fillable = array();

    /**
     * Alias Id
     *
     * @return int
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function getId()
    {
        return implode('_', array($this->getAttribute('attachment_id'), $this->getAttribute('product_id')));
    }

}
