<?php

namespace App\Modules\Order\Models\Traits;

use App\Modules\Activitylog\Models\Entities\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

trait ItemActivity
{

//    use LogsActivity;
//
//    protected static $logName = 'order.item';
//
//    protected static $logOnlyDirty = true;
//
//    protected static $logAttributes = array(
//        'variant_id',
//        'quantity',
//        'price',
//    );
//
//    public function getActivityUpdatedMessage(Activity $activity)
//    {
//        return strtr('Thay đổi số lượng :originalQuantity => :newQuantity', array(
//            ':originalQuantity' => $activity->getExtraProperty('old.quantity'),
//            ':newQuantity' => $activity->getExtraProperty('attributes.quantity'),
//        ));
//    }
//
//    public function getActivityDeletedMessage(Activity $activity)
//    {
//        return 'Đã xóa bản ghi';
//    }
//
//    /**
//     * To Log Ref Resource
//     *
//     * @return array
//     * @author xuanhieupd
//     */
//    public function toRefResource()
//    {
//        return array(
//            'id' => $this->getId(),
//            'order_id' => $this->getAttribute('order_id'),
//            'product_id' => $this->getAttribute('product_id'),
//            'variant_id' => $this->getAttribute('variant_id'),
//        );
//    }
}
