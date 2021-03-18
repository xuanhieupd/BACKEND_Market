<?php

namespace App\Modules\Product\Modules\Seen\Traits;

use App\Modules\Product\Models\Entities\Product;
use App\Modules\Product\Modules\Seen\Models\Entities\Seen;

trait Seenable
{

    /**
     * Thêm vào danh sách đã xem
     *
     * @param Product $productInfo
     * @return Seen|false
     */
    public function seenable(Product $productInfo)
    {
        $seenInfo = Seen::query()
            ->where('product_id', $productInfo->getId())
            ->where('user_id', $this->getId())
            ->first();

        if ($seenInfo) return true;

        $seenInfo = new Seen(array(
            'product_id' => $productInfo->getId(),
            'user_id' => $this->getId(),
        ));

        return $seenInfo->save() ? $seenInfo : false;

    }

}
