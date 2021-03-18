<?php

/**
 * Permission App Provider
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Permission
 * @copyright (c) 21.11.2020, HNW
 */

namespace App\Modules\Permission\Providers;

use App\Modules\Permission\Models\Repositories\Contracts\PermissionGroupInterface;
use App\Modules\Permission\Models\Repositories\Contracts\PermissionInterface;
use App\Modules\Permission\Models\Repositories\Eloquents\PermissionGroupRepository;
use App\Modules\Permission\Models\Repositories\Eloquents\PermissionRepository;
use Illuminate\Support\ServiceProvider;

class PermissionAppProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function boot()
    {
        $this->app->singleton(PermissionInterface::class, PermissionRepository::class);

        $this->app->singleton(PermissionGroupInterface::class, PermissionGroupRepository::class);
    }

}
