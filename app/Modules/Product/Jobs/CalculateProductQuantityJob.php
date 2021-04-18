<?php

namespace App\Modules\Product\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CalculateProductQuantityJob implements ShouldQueue
{

    use InteractsWithQueue;

    const ACTION_STOCK_PURCHASE = 1;
    const ACTION_ORDER_CART = 2;
    const ACTION_ORDER_DRAFT = 3;
    const ACTION_ORDER_UPDATE = 4;
    const ACTION_ORDER_DELETE = 5;
    const ACTION_STOCK_TAKE = 6;
    const ACTION_BILL_RETURN_CUSTOMER = 7;
    const ACTION_BILL_RETURN_SUPPLIER = 8;

    protected $model;
    protected $action;
    protected $historyData;

    public function __construct($model, $action = null, $historyData = array())
    {
        $this->model = $model;
        $this->action = $action;
        $this->historyData = $historyData;
    }

    public function handle()
    {

    }

}
