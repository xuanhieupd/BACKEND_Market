<?php

/**
 * Attachment App Provider
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Attachment
 * @copyright (c) 07.10.2020, HNW
 */

namespace App\Modules\Attachment\Providers;

use App\Modules\Attachment\Models\Repositories\Contracts\AttachmentInterface;
use App\Modules\Attachment\Models\Repositories\Eloquents\AttachmentRepository;
use Illuminate\Support\ServiceProvider;

class AttachmentAppProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function boot()
    {
        $this->app->singleton(AttachmentInterface::class, AttachmentRepository::class);
    }

}
