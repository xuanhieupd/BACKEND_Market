<?php

/**
 * Lịch sử giao dịch
 *
 * @author xuanhieupd
 * @package Wallet
 * @copyright 06.10.2020
 */

namespace App\Modules\Wallet\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Wallet\Resources\TransactionResource;
use Bavix\Wallet\Interfaces\Wallet;
use Illuminate\Http\Request;

abstract class TransactionsController extends AbstractController
{

    /**
     * Danh sách lịch sử giao dịch
     *
     * @param Request $request
     * @return TransactionResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $walletInfo = $this->getWallet($request);
        $transactions = $walletInfo->transactions;

        return TransactionResource::collection($transactions);
    }

    public abstract function getWallet(Request $request): Wallet;

}
