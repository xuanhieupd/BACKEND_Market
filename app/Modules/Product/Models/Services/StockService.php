<?php

/**
 * Stock Service
 *
 * @author xuanhieupd
 * @package Product
 * @copyright 04.102020, HNW
 */

namespace App\Modules\Product\Models\Services;

use App\Modules\Product\Models\Services\Contracts\AlterStockInterface;
use App\Modules\Product\Modules\StockTracking\Models\Entities\StockTracking;
use App\Modules\User\Models\Entities\User;
use Illuminate\Support\Collection;

class StockService
{

    /**
     * Xử lý stocks
     *
     * @param Collection $collections
     * @author xuanhieupd
     */
    public function handles(User $userInfo, Collection $stockDaos)
    {
        $trackingInserts = collect();

        foreach ($stockDaos as $stockDao) {
            $trackingInfo = new StockTracking();

            $trackingInfo->setAttribute('user_id', $userInfo->getId());
            $trackingInfo->setAttribute('stock', $stockDao->getStock());

            $trackingInfo->setAttribute('variant_id', $stockDao->getHolder()->getVariantId());
            $trackingInfo->setAttribute('holder_id', $stockDao->getHolder()->getId());
            $trackingInfo->setAttribute('holder_type', get_class($stockDao->getHolder()));

            $trackingInserts->push();
        }
    }

    protected function handle(AlterStockInterface $alterStock)
    {

    }


}
