<?php

/**
 * App Service Provider
 *
 * @author xuanhieupd
 * @package Slide
 * @copyright (c) 18.02.2020, HNW
 */

namespace App\Modules\Slide\Providers;

use App\Modules\Slide\Models\Repositories\Contracts\SlideInterface;
use App\Modules\Slide\Models\Repositories\Eloquents\SlideRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @author xuanhieupd
     */
    public function boot()
    {
        $this->app->bind(SlideInterface::class, SlideRepository::class);
    }

}
