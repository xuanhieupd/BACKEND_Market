<?php

Route::group(array('prefix' => 'category', 'as' => 'category.'), function () {
    Route::group(array('middleware' => array('auth:api')), function () {
        Route::get('/all', array('as' => 'all', 'uses' => 'AllController@actionIndex'));
        Route::get('/list', array('as' => 'list', 'uses' => 'CategoriesController@actionIndex'));
    });
    Route::get('/main', array('as' => 'main', 'uses' => 'CategoriesController@actionMain'));
});
