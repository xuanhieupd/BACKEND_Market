<?php

/**
 * Feed App Service Provider
 *
 * @author xuanhieupd
 * @package Feed
 * @copyright (c) 06.10.2020, HNW
 */

namespace App\Modules\Feed\Providers;

use App\Modules\Feed\Models\Repositories\Contracts\FeedInterface;
use App\Modules\Feed\Models\Repositories\FeedRepository;
use App\Modules\Feed\Modules\Comment\Models\Repositories\CommentRepository;
use App\Modules\Feed\Modules\Comment\Models\Repositories\Contracts\CommentInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Boot
     *
     * @return void
     * @author xuanhieupd
     */
    public function boot()
    {
        $this->app->bind(FeedInterface::class, FeedRepository::class);

        $this->app->bind(CommentInterface::class, CommentRepository::class);
    }

}
