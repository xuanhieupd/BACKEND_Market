<?php

use App\Modules\Customer\Middleware\CustomerMiddleware;

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'customer', 'as' => 'customer.'), function () {
    Route::group(array('middleware' => array(CustomerMiddleware::class)), function () {
        Route::get('/{customerId}/phoneNumbers', array('as' => 'phone.index', 'uses' => 'PhoneNumbersController@actionIndex'));
    });
});
