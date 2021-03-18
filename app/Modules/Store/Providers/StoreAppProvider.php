<?php

/**
 * Store App Provider
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Store
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Store\Providers;

use App\Modules\Store\Models\Repositories\Contracts\StoreInterface;
use App\Modules\Store\Models\Repositories\Eloquents\StoreRepository;
use Illuminate\Support\ServiceProvider;

class StoreAppProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function boot()
    {
        $this->app->singleton(StoreInterface::class, StoreRepository::class);
    }

}
