<?php

use App\Modules\Feed\Http\Middleware\FeedMiddleware;

Route::group(array('middleware' => array('auth:api')), function () {
    Route::get('/feeds', array('as' => 'feed.list', 'uses' => 'FeedsController@actionIndex'));
    Route::get('/feed/{feedId}/detail', array('as' => 'feed.detail', 'uses' => 'FeedController@actionIndex'));
    Route::get('/feed/{feedId}/likes', array('as' => 'feed.likes', 'uses' => 'FeedLikesController@actionIndex'))->middleware(FeedMiddleware::class);

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
