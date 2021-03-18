<?php

/**
 * Stock Tracking Service
 *
 * @author xuanhieupd
 * @package StockTracking
 * @copyright 04.10.2020
 */

namespace App\Modules\Product\Modules\StockTracking\Models\Services;

use App\Modules\Product\Models\Services\Contracts\AlterStockInterface;
use App\Modules\Product\Modules\StockTracking\Models\Entities\StockTracking;
use App\Modules\User\Models\Entities\User;
use Illuminate\Support\Collection;

class StockTrackingService
{

    /**
     * Handle danh sách bản ghi thay đổi số lượng
     *
     * @param Collection $collections
     */
    public function handlers(User $userInfo, Collection $collections)
    {
        $trackingInserts = array();

        foreach ($collections as $alterStock) {
            $trackingInserts[] = $this->_getTrackingInfo($userInfo, $alterStock)->toArray();
        }

        StockTracking::insert($trackingInserts);
    }

    /**
     * Init model Stock Tracking
     *
     * @param User $userInfo
     * @param AlterStockInterface $alterStock
     * @return StockTracking
     */
    protected function _getTrackingInfo(User $userInfo, AlterStockInterface $alterStock)
    {
        return new StockTracking(array(
            'user_id' => $userInfo->getId(),
            'holder_id' => $alterStock->getId(),
            'holder_type' => get_class($alterStock),
            'variant_id' => $alterStock->getAlterStockVariantId(),
            'stock' => $alterStock->getAlterStockValue()
        ));
    }

}
