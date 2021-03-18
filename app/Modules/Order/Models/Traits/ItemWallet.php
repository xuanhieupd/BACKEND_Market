<?php

/**
 * Item Wallet Trait
 * 
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Order
 * @copyright (c) 03.10.2020, HNW
 */

namespace App\Modules\Order\Models\Traits;

use Bavix\Wallet\Interfaces\Customer;

trait ItemWallet {

    public function getUniqueId(): string {
        return \Illuminate\Support\Str::random();
    }

    public function canBuy(Customer $customer, int $quantity = 1, bool $force = null): bool {
        return true;
    }

    public function getAmountProduct(Customer $customer) {
        return 2;
    }

    public function getMetaProduct(): array {
        return [
            'title' => $this->title,
            'description' => 'Purchase of Product #' . $this->id,
        ];
    }

}
