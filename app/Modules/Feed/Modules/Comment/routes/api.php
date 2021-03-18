<?php

use App\Modules\Feed\Http\Middleware\FeedMiddleware;
use App\Modules\Feed\Modules\Comment\Http\Middleware\CommentMiddleware;

Route::group(array('middleware' => array('auth:api', FeedMiddleware::class), 'prefix' => 'feed', 'as' => 'feed.'), function () {
    Route::get('/{feedId}/comments', array('as' => 'comment.list', 'uses' => 'CommentsController@actionIndex'));
    Route::post('/{feedId}/comment', array('as' => 'comment.do', 'uses' => 'CommentController@actionIndex'));
});

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'store', 'as' => 'store.'), function () {
    Route::group(array('middleware' => array(FeedMiddleware::class), 'prefix' => 'feed', 'as' => 'feed.'), function () {
        Route::post('/{feedId}/comment', array('as' => 'comment.do', 'uses' => 'Store\CommentController@actionIndex'));
    });
});

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'feed', 'as' => 'feed.'), function () {
    Route::group(array('prefix' => 'comment', 'as' => 'comment.'), function () {
        Route::post('/{commentId}/delete', array('as' => 'delete', 'uses' => 'DeleteController@actionIndex'))->middleware(CommentMiddleware::class);
    });
});
