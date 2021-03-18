<?php

Route::group(array('middleware' => array('web'), 'prefix' => 'attachment', 'as' => 'attachment.'), function () {
    Route::group(array('prefix' => 'p', 'as' => 'product.'), function () {
        Route::get('/{productId}/full.jpg', array('as' => 'full', 'uses' => 'FullController@actionIndex'));
        Route::get('/{productId}/thumb.jpg', array('as' => 'thumb', 'uses' => 'ThumbController@actionIndex'));
    });
});
