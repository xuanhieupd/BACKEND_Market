<?php

/**
 * ECartSubmited - Sự kiện khi xác nhận thành công giỏ hàng
 *
 * @author xuanhieupd
 * @package Cart
 * @copyright 13.11.2020, HNW
 */

namespace App\Modules\Cart\Events;

use App\Modules\Order\Models\Entities\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ECartSubmited
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
     * @return Order
     * @author xuanhieupd
     */
    public function getOrder()
    {
        return $this->orderInfo;
    }


}
