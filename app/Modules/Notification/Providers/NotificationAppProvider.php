<?php

namespace App\Modules\Notification\Providers;

use App\Modules\Notification\Models\Repositories\Contracts\TokenInterface;
use App\Modules\Notification\Models\Repositories\Eloquents\TokenRepository;
use Illuminate\Support\ServiceProvider;

class NotificationAppProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function boot()
    {
        $this->app->singleton(TokenInterface::class, TokenRepository::class);
    }

}
