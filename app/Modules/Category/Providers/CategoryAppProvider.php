<?php

/**
 * Category App Provider
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Category
 * @copyright (c) 19.11.2020, HNW
 */

namespace App\Modules\Category\Providers;

use App\Modules\Category\Models\Repositories\Contracts\CategoryInterface;
use App\Modules\Category\Models\Repositories\Eloquents\CategoryRepository;
use Illuminate\Support\ServiceProvider;

class CategoryAppProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function boot()
    {
        $this->app->singleton(CategoryInterface::class, CategoryRepository::class);
    }

}
