<?php

/**
 * Product App Provider
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Product
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Product\Providers;

use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;

use App\Modules\Product\Modules\Attachment\Models\Repositories\Contracts\AttachmentInterface;
use App\Modules\Product\Modules\Attachment\Models\Repositories\Eloquents\AttachmentRepository;

use App\Modules\Product\Modules\Color\Models\Repositories\Contracts\ColorInterface;
use App\Modules\Product\Modules\Color\Models\Repositories\Eloquents\ColorRepository;

use App\Modules\Product\Modules\Seen\Models\Repositories\Contracts\SeenInterface;
use App\Modules\Product\Modules\Seen\Models\Repositories\Eloquents\SeenRepository;
use App\Modules\Product\Modules\StockTracking\Models\Repositories\Contracts\StockTrackingInterface;
use App\Modules\Product\Modules\StockTracking\Models\Repositories\Eloquents\StockTrackingRepository;

use App\Modules\Product\Modules\Variant\Models\Repositories\Contracts\VariantInterface;
use App\Modules\Product\Modules\Variant\Models\Repositories\Eloquents\VariantRepository;

use App\Modules\Product\Modules\Size\Models\Repositories\Contracts\SizeInterface;
use App\Modules\Product\Modules\Size\Models\Repositories\Eloquents\SizeRepository;

use Illuminate\Support\ServiceProvider;

class ProductAppProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function boot()
    {
        $this->app->singleton(ProductInterface::class, ProductRepository::class);

        $this->app->singleton(VariantInterface::class, VariantRepository::class);

        $this->app->singleton(ColorInterface::class, ColorRepository::class);

        $this->app->singleton(SizeInterface::class, SizeRepository::class);

        $this->app->singleton(StockTrackingInterface::class, StockTrackingRepository::class);

        $this->app->singleton(AttachmentInterface::class, AttachmentRepository::class);

        $this->app->singleton(SeenInterface::class, SeenRepository::class);
    }

}
