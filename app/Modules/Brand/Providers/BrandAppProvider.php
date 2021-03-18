<?php

/**
 * Brand App Provider
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Brand
 * @copyright (c) 20.11.2020, HNW
 */

namespace App\Modules\Brand\Providers;

use App\Modules\Brand\Models\Repositories\Contracts\BrandInterface;
use App\Modules\Brand\Models\Repositories\Eloquents\BrandRepository;
use Illuminate\Support\ServiceProvider;

class BrandAppProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function boot()
    {
        $this->app->singleton(BrandInterface::class, BrandRepository::class);
    }

}
