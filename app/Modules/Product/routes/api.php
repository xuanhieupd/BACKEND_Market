<?php

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'product', 'as' => 'product.'), function () {
    Route::get('/list', array('as' => 'index', 'uses' => 'ProductsController@actionIndex'));
    Route::get('/{productId}/variants', array('as' => 'variant.index', 'uses' => 'VariantController@actionIndex'));

    Route::get('/{productId}/detail', array('as' => 'detail', 'uses' => 'ProductDetailController@actionIndex'));
    Route::get('/search', array('as' => 'search', 'uses' => 'SearchController@actionIndex'));

    Route::post('/add', array('as' => 'add', 'uses' => 'AddController@actionIndex'));
    Route::post('/delete', array('as' => 'add', 'uses' => 'DeleteController@actionIndex'));
});
