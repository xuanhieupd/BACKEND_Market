<?php

/**
 * Wallet Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Wallet
 * @copyright (c) 05.10.2020, HNW
 */

namespace App\Modules\Wallet\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Store\Models\Entities\Store;
use App\Modules\Wallet\Models\Entities\Wallet;
use App\Modules\Wallet\Models\Repositories\Contracts\WalletInterface;

class WalletRepository extends AbstractRepository implements WalletInterface
{

    public function getWallet(Store $storeInfo)
    {
        $storeInfo->createWallet();
    }

    /**
     * @return Wallet
     */
    public function model()
    {
        return Wallet::class;
    }

}
