<?php

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'user', 'as' => 'user.'), function () {
    Route::get('/', array('as' => 'index', 'uses' => 'IndexController@actionIndex'));
    Route::get('/{userId}/detail', array('as' => 'detail', 'uses' => 'UserController@actionIndex'));
});
