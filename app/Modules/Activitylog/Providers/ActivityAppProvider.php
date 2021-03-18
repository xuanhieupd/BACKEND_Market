<?php

/**
 * Activity App Provider
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Activitylog
 * @copyright (c) 10.10.2020, HNW
 */

namespace App\Modules\Activitylog\Providers;

use App\Modules\Activitylog\Models\Repositories\Contracts\ActivityInterface;
use App\Modules\Activitylog\Models\Repositories\Eloquents\ActivityRepository;
use App\Modules\Customer\Modules\CustomerPhone\Models\Repositories\Eloquents\CustomerAddressRepository;
use App\Modules\Customer\Modules\CustomerPhone\Models\Repositories\Eloquents\CustomerPhoneRepository;
use Illuminate\Support\ServiceProvider;

class ActivityAppProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function boot()
    {
        $this->app->singleton(ActivityInterface::class, ActivityRepository::class);

    }

}
