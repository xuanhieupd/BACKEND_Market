<?php

Route::group(array('middleware' => array(), 'prefix' => 'supplier', 'as' => 'supplier.'), function() {
    Route::get('/', array('as' => 'index', 'uses' => 'IndexController@actionIndex'));
});
