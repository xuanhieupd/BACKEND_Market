<?php

/**
 * Tính lại tổng số lượng và tổng tiền đơn hàng
 *
 * @author xuanhieupd
 * @package Order
 * @copyright 09.10.2020
 */

namespace App\Modules\Order\Listeners;

use App\Modules\Order\Events\EItemUpdated;
use App\Modules\Order\Models\Repositories\Contracts\ItemInterface;
use App\Modules\Order\Models\Repositories\Eloquents\ItemRepository;

class LOrderCalculate
{

    /**
     * @var ItemRepository
     */
    protected $itemRepo;

    /**
     * Create the event listener.
     *
     * @param ItemInterface $itemRepo
     * @return void
     * @author xuanhieupd
     */
    public function __construct(ItemInterface $itemRepo)
    {
        $this->itemRepo = $itemRepo;
    }

    /**
     * Handle the event.
     *
     * @param EItemUpdated $event
     * @return void
     * @author xuanhieupd
     */
    public function handle(EItemUpdated $event)
    {
        $orderInfo = $event->getOrder();
        $items = $this->itemRepo->getItemsByOrderId($orderInfo->getId(), array(), array(
            'fields' => array(
                'item_id',
                'quantity',
                'price',
            )
        ));

        $totalQuantity = 0;
        $totalPrice = 0;

        foreach ($items as $item) {
            $totalQuantity += $item->getQuantity();
            $totalPrice += $item->getTotalPrice();
        }

        $orderInfo->setAttribute('total_quantity', $totalQuantity);
        $orderInfo->setAttribute('total_price', $totalPrice);
        $orderInfo->save();
    }
}
