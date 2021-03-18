<?php

namespace App\Modules\Role\Providers;

use App\Modules\Role\Models\Repositories\Contracts\RoleInterface;
use App\Modules\Role\Models\Repositories\Eloquents\RoleRepository;
use App\Modules\Role\Modules\Permission\Models\Repositories\Contracts\PermissionInterface;
use App\Modules\Role\Modules\Permission\Models\Repositories\Eloquents\PermissionRepository;
use Illuminate\Support\ServiceProvider;

class RoleAppProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function boot()
    {
        $this->app->singleton(RoleInterface::class, RoleRepository::class);

        $this->app->singleton(PermissionInterface::class, PermissionRepository::class);
    }

}
