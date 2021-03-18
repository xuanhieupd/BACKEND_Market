<?php

/**
 * Supplier App Provider
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Supplier
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Supplier\Providers;

use App\Modules\Customer\Modules\CustomerPhone\Models\Repositories\Eloquents\CustomerAddressRepository;
use App\Modules\Supplier\Models\Repositories\Contracts\SupplierInterface;
use App\Modules\Supplier\Models\Repositories\Eloquents\SupplierRepository;
use App\Modules\Supplier\Modules\Address\Models\Repositories\Contracts\SupplierAddressInterface;
use App\Modules\Supplier\Modules\Address\Models\Repositories\Eloquents\SupplierAddressRepository;
use App\Modules\Supplier\Modules\CustomerPhone\Models\Repositories\Eloquents\SupplierPhoneRepository;
use App\Modules\Supplier\Modules\PhoneNumber\Models\Repositories\Contracts\SupplierPhoneInterface;
use Illuminate\Support\ServiceProvider;

class SupplierAppProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function boot()
    {
        $this->app->singleton(SupplierInterface::class, SupplierRepository::class);
        $this->app->singleton(SupplierPhoneInterface::class, SupplierPhoneRepository::class);
        $this->app->singleton(SupplierAddressInterface::class, SupplierAddressRepository::class);
    }

}
