<?php

use App\Modules\Order\Middleware\OrderMiddleware;

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'order', 'as' => 'order.'), function () {
    Route::get('/', array('as' => 'index', 'uses' => 'OrdersController@actionIndex'));
    Route::post('/add', array('as' => 'add', 'uses' => 'AddController@actionIndex'));

    Route::group(array('middleware' => array(OrderMiddleware::class)), function () {
        Route::get('/{orderId}/detail', array('as' => 'detail', 'uses' => 'DetailController@actionIndex'));
        Route::get('/{orderId}/activities', array('as' => 'activity.index', 'uses' => 'ActivitiesController@actionIndex'));

        Route::post('/{orderId}/toWarehouse', array('as' => 'routing.toWarehouse', 'uses' => 'ToWarehouseController@actionIndex'));
        Route::post('/{orderId}/okWarehouse', array('as' => 'submit', 'uses' => 'SubmitController@actionIndex'));
        Route::post('/{orderId}/submit', array('as' => 'submit', 'uses' => 'SubmitController@actionIndex'));

        Route::post('/{orderId}/delete', array('as' => 'delete', 'uses' => 'DeleteController@actionIndex'));
    });

    Route::group(array('middleware' => array(OrderMiddleware::class), 'prefix' => 'change', 'as' => 'change.'), function () {
        Route::post('/{orderId}/customer', array('as' => 'customer', 'uses' => 'Change\CustomerController@actionIndex'));
        Route::post('/{orderId}/quantity', array('as' => 'quantity', 'uses' => 'Change\QuantityController@actionIndex'));
        Route::post('/{orderId}/price', array('as' => 'price', 'uses' => 'Change\PriceController@actionIndex'));
        Route::post('/{orderId}/delete', array('as' => 'delete', 'uses' => 'Change\DeleteController@actionIndex'));
    });
});
