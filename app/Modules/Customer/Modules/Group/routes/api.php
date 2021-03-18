<?php

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'customer', 'as' => 'customer.'), function () {
    Route::group(array('prefix' => 'group', 'as' => 'group.'), function () {
        Route::get('/', array('as' => 'index', 'uses' => 'CustomerGroupsController@actionIndex'));
        Route::post('/save', array('as' => 'save', 'uses' => 'SaveController@actionIndex'));
        Route::post('/delete', array('as' => 'delete', 'uses' => 'DeleteController@actionIndex'));
    });
});
