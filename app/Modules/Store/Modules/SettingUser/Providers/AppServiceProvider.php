<?php

/**
 * App Service Provider
 *
 * @author xuanhieupd
 * @package SettingUser
 * @copyright (c) 04.02.2021, HNW
 */

namespace App\Modules\Store\Modules\SettingUser\Providers;

use App\Modules\Store\Modules\SettingUser\Models\Repositories\Contracts\SettingUserInterface;
use App\Modules\Store\Modules\SettingUser\Models\Repositories\Eloquents\SettingUserRepository;
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
        $this->app->bind(SettingUserInterface::class, SettingUserRepository::class);
    }

}
