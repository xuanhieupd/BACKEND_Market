<?php

/**
 * Order App Provider
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Order
 * @copyright (c) 03.10.2020, HNW
 */

namespace App\Modules\Order\Providers;

use App\Modules\Order\Events\EItemUpdated;
use App\Modules\Order\Listeners\LOrderCalculate;
use App\Modules\Order\Models\Repositories\Contracts\ItemInterface;
use App\Modules\Order\Models\Repositories\Contracts\OrderInterface;
use App\Modules\Order\Models\Repositories\Eloquents\ItemRepository;
use App\Modules\Order\Models\Repositories\Eloquents\OrderRepository;
use App\Modules\Order\Modules\Note\Models\Repositories\Contracts\NoteInterface;
use App\Modules\Order\Modules\Note\Models\Repositories\Eloquents\NoteRepository;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class OrderAppProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function boot()
    {
        $this->app->singleton(OrderInterface::class, OrderRepository::class);

        $this->app->singleton(ItemInterface::class, ItemRepository::class);

        $this->app->singleton(NoteInterface::class, NoteRepository::class);

        Event::listen(EItemUpdated::class, LOrderCalculate::class);
    }

}
