<?php

use App\Modules\Store\Middleware\StoreMiddleware;

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'store', 'as' => 'store.'), function () {
    Route::get('/{storeId}/categories', array('as' => 'categories', 'uses' => 'CategoriesController@actionIndex'))->middleware(StoreMiddleware::class);
});
