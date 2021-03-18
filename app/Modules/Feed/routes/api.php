<?php

Route::group(array('middleware' => array('auth:api')), function () {
    Route::get('/feeds', array('as' => 'feed.list', 'uses' => 'FeedsController@actionIndex'));

    Route::group(array('prefix' => 'feed', 'as' => 'feed.'), function () {
        Route::post('/save', array('as' => 'save', 'uses' => 'SaveController@actionIndex'));
    });
});

Route::group(array('middleware' => array('auth:api')), function () {
    Route::group(array('prefix' => 'store', 'as' => 'store.'), function () {
        Route::group(array('prefix' => 'feed', 'as' => 'feed.'), function () {
            Route::post('/save', array('as' => 'save', 'uses' => 'Store\SaveController@actionIndex'));
        });
    });
});
