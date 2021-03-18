<?php

use App\Modules\Store\Middleware\StoreMiddleware;

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'store', 'as' => 'store.'), function () {
    Route::get('/list', array('as' => 'index', 'uses' => 'StoresController@actionIndex'));
    Route::get('/{storeId}/detail', array('as' => 'detail', 'uses' => 'StoreController@actionIndex'))->middleware(StoreMiddleware::class);
});
