<?php

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'share', 'as' => 'share.'), function () {

});
