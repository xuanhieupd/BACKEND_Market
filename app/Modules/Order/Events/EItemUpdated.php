<?php

namespace App\Modules\Order\Events;

use App\Modules\Order\Models\Entities\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EItemUpdated
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $orderInfo;

    /**
     * Create a new event instance.
     *
     * @param Order $orderInfo
     * @return void
     * @author xuanhieupd
     */
    public function __construct(Order $orderInfo)
    {
        $this->orderInfo = $orderInfo;
    }

    /**
     * Lấy thông tin đơn hàng
     *
     * @author xuanhieupd
     * @return Order
     */
    public function getOrder()
    {
        return $this->orderInfo;
    }

}
