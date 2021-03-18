<?php

/**
 * Customer App Provider
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Customer
 * @copyright (c) 03.10.2020, HNW
 */

namespace App\Modules\Customer\Providers;

use App\Modules\Customer\Models\Repositories\Contracts\CustomerInterface;
use App\Modules\Customer\Models\Repositories\Eloquents\CustomerRepository;
use App\Modules\Customer\Modules\Address\Models\Repositories\Contracts\CustomerAddressInterface;
use App\Modules\Customer\Modules\Address\Models\Repositories\Eloquents\CustomerAddressRepository;
use App\Modules\Customer\Modules\PhoneNumber\Models\Repositories\Eloquents\CustomerPhoneRepository;
use App\Modules\Customer\Modules\Group\Models\Repositories\Contracts\GroupInterface;
use App\Modules\Customer\Modules\Group\Models\Repositories\Eloquents\GroupRepository;
use App\Modules\Customer\Modules\PhoneNumber\Models\Repositories\Contracts\CustomerPhoneInterface;
use Illuminate\Support\ServiceProvider;

class CustomerAppProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function boot()
    {
        $this->app->singleton(CustomerInterface::class, CustomerRepository::class);
        $this->app->singleton(CustomerPhoneInterface::class, CustomerPhoneRepository::class);
        $this->app->singleton(CustomerAddressInterface::class, CustomerAddressRepository::class);
        $this->app->singleton(GroupInterface::class, GroupRepository::class);
    }

}
