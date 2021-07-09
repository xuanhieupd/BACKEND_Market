<?php

namespace App\Modules\ShortUrl\Providers;

use App\Modules\ShortUrl\Models\Repositories\Contracts\ShortUrlInterface;
use App\Modules\ShortUrl\Models\Repositories\Eloquents\ShortUrlRepository;
use Illuminate\Support\ServiceProvider;

class ShortUrlAppProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function boot()
    {
        $this->app->singleton(ShortUrlInterface::class, ShortUrlRepository::class);
    }

}
