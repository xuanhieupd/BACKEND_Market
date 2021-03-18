<?php

/**
 * Wallet Model
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Wallet
 * @copyright (c) 03.10.2020, HNW
 */

namespace App\Modules\Wallet\Models\Entities;

use Bavix\Wallet\Models\Wallet as BaseWallet;

class Wallet extends BaseWallet
{

    protected $connection = 'mysql';
}
