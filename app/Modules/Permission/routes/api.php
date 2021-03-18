<?php

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'permission', 'as' => 'permission.'), function () {
    Route::get('/', array('as' => 'index', 'uses' => 'PermissionsController@actionIndex'));
    Route::post('/save', array('as' => 'save', 'uses' => 'SaveController@actionIndex'));
});
