<?php

Route::group(array('prefix' => 'auth', 'as' => 'auth.'), function () {
    Route::post('/login', array('as' => 'login', 'uses' => 'LoginController@actionIndex'));
    Route::post('/register', array('as' => 'register', 'uses' => 'RegisterController@actionIndex'));
});

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'auth', 'as' => 'auth.'), function () {
    Route::post('/profile', array('as' => 'profile', 'uses' => 'ProfileController@actionIndex'));
});
