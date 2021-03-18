<?php

/**
 * User App Provider
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package User
 * @copyright (c) 03.10.2020, HNW
 */

namespace App\Modules\User\Providers;

use App\Modules\User\Models\Repositories\Contracts\ProfileInterface;
use App\Modules\User\Models\Repositories\Contracts\UserInterface;
use App\Modules\User\Models\Repositories\Eloquents\ProfileRepository;
use App\Modules\User\Models\Repositories\Eloquents\UserRepository;
use Illuminate\Support\ServiceProvider;

class UserAppProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function boot()
    {
        $this->app->singleton(UserInterface::class, UserRepository::class);

        $this->app->singleton(ProfileInterface::class, ProfileRepository::class);
    }

}
