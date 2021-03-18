<?php

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'testing', 'as' => 'testing.'), function() {
    Route::get('/', array('as' => 'index', 'uses' => 'TestingController@actionIndex'));
});
