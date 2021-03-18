<?php

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'size', 'as' => 'size.'), function () {
    Route::get('/', array('as' => 'index', 'uses' => 'SizesController@actionIndex'));
});
