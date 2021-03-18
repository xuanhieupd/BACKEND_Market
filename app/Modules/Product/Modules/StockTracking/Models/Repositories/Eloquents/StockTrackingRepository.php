<?php

/**
 * Stock Tracking Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package StockTracking
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Product\Modules\StockTracking\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Product\Modules\StockTracking\Models\Entities\StockTracking;
use App\Modules\Product\Modules\StockTracking\Models\Repositories\Contracts\StockTrackingInterface;

class StockTrackingRepository extends AbstractRepository implements StockTrackingInterface
{

    /**
     * @return StockTracking
     */
    public function model()
    {
        return StockTracking::class;
    }

}
