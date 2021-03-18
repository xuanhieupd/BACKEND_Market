<?php

/**
 * Response Service Provider
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package XuanHieu
 * @copyright (c) 5.12.2017, HNW
 */

namespace App\Response;

use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{

    /**
     *
     * @author shin_conan <xuanhieu.pd@gmail.com>
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->make(Response::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function register()
    {

    }

}
