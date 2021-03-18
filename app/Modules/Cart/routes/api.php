<?php

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'cart', 'as' => 'cart.'), function () {
    Route::get('/', array('as' => 'index', 'uses' => 'IndexController@actionIndex'));
    Route::post('/add', array('as' => 'add', 'uses' => 'AddController@actionIndex'));
    Route::post('/submit', array('as' => 'submit', 'uses' => 'SubmitController@actionIndex'));
    Route::post('/delete', array('as' => 'delete', 'uses' => 'DeleteCartController@actionIndex'));
});
