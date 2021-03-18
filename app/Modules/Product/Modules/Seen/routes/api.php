<?php

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'seen', 'as' => 'seen.'), function () {
    Route::get('/list', array('as' => 'list', 'uses' => 'SeensController@actionIndex'));
});
