<?php

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'slide', 'as' => 'slide.'), function () {
    Route::get('/list', array('as' => 'index', 'uses' => 'SlidesController@actionIndex'));
});
