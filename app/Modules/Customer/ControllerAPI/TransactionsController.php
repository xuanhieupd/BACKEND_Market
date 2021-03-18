<?php

namespace App\Modules\Customer\ControllerAPI;

use App\Modules\Wallet\ControllerAPI\TransactionsController as BaseController;
use Bavix\Wallet\Interfaces\Wallet;
use Illuminate\Http\Request;

class TransactionsController extends BaseController
{

    public function getWallet(Request $request): Wallet
    {
        return $request->input('customer');
    }
}
