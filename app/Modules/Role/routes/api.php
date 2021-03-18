<?php

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'role', 'as' => 'role.'), function () {
    Route::get('/', array('as' => 'index', 'uses' => 'RolesController@actionIndex'));
    Route::get('/{roleId}/detail', array('as' => 'detail', 'uses' => 'RoleController@actionIndex'));
});
