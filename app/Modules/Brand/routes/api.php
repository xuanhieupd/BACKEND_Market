<?php

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'brand', 'as' => 'brand.'), function () {
    Route::get('/', array('as' => 'index', 'uses' => 'BrandsController@actionIndex'));
});
