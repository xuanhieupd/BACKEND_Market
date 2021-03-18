<?php

/**
 * Cart App Provider
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Cart
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Cart\Providers;

use App\Modules\Cart\Models\Repositories\Contracts\OrderCartInterface;
use App\Modules\Cart\Models\Repositories\Eloquents\OrderCartRepository;
use Illuminate\Support\ServiceProvider;

class CartAppProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function boot()
    {
        $this->app->singleton(OrderCartInterface::class, OrderCartRepository::class);
    }

}
