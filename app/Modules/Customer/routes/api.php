<?php

use App\Modules\Customer\Middleware\CustomerMiddleware;

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'customer', 'as' => 'customer.'), function () {
    Route::get('/', array('as' => 'index', 'uses' => 'CustomersController@actionIndex'));

    Route::group(array('middleware' => array(CustomerMiddleware::class)), function () {
        Route::get('/{customerId}/detail', array('as' => 'detail', 'uses' => 'DetailController@actionIndex'));
        Route::get('/{customerId}/transactions', array('as' => 'transaction.index', 'uses' => 'TransactionsController@actionIndex'));
    });
});
