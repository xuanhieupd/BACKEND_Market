<?php

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'color', 'as' => 'color.'), function () {
    Route::get('/', array('as' => 'index', 'uses' => 'ColorsController@actionIndex'));
});
