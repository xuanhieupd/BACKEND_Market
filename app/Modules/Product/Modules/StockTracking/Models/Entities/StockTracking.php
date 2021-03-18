<?php

/**
 * Stock Tracking Model
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package StockTracking
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Product\Modules\StockTracking\Models\Entities;

use App\Base\AbstractModel;

class StockTracking extends AbstractModel
{

    protected $table = 'hnw_stock_tracking';
    protected $primaryKey = 'tracking_id';
    public static $tableAlias = 'hnw_stock_tracking';

    protected $fillable = array(
        'user_id',
        'variant_id',
        'stock',
        'holder_id',
        'holder_type',
    );

    /**
     * Alias for `tracking_id`
     *
     * @return int
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function getId()
    {
        return $this->getAttribute('tracking_id');
    }

}
