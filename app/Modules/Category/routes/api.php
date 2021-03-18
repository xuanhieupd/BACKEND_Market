<?php

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'category', 'as' => 'category.'), function () {
    Route::get('/all', array('as' => 'all', 'uses' => 'AllController@actionIndex'));
    Route::get('/list', array('as' => 'list', 'uses' => 'CategoriesController@actionIndex'));
});
