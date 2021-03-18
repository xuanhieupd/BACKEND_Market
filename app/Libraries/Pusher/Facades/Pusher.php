<?php

/**
 * Pusher Facades
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Pusher
 * @copyright (c) 22.03.2020, HNW
 */

namespace App\Libraries\Pusher\Facades;

use Illuminate\Support\Facades\Facade;

class Pusher extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     * @throws \Pusher\PusherException
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    protected static function getFacadeAccessor() {
        $configInfo = config('broadcasting.connections.pusher');
        return new \Pusher\Pusher($configInfo['key'], $configInfo['secret'], $configInfo['app_id']);
    }

}
