<?php

use App\Modules\Order\Middleware\OrderMiddleware;

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'order', 'as' => 'order.'), function () {
    Route::group(array('middleware' => array(OrderMiddleware::class)), function () {
        Route::get('/{orderId}/notes', array('as' => 'note.index', 'uses' => 'NotesController@actionIndex'));
    });
});
