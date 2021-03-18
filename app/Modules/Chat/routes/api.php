<?php

use App\Modules\Chat\Middleware\ConversationMiddleware;
use App\Modules\Chat\Middleware\MessageMiddleware;

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'chat', 'as' => 'chat.'), function () {
    Route::post('/authorize', array('as' => 'authorize', 'uses' => 'AuthorizeController@actionIndex'));
    Route::post('/create', array('as' => 'conversation.create', 'uses' => 'CreateController@actionIndex'));
    Route::post('/conversation/get', array('as' => 'conversation.get', 'uses' => 'ConversationController@actionIndex'));

    Route::get('/conversations', array('as' => 'conversation.index', 'uses' => 'ConversationsController@actionIndex'));
    Route::get('/c/{conversationId}/messages', array('as' => 'conversation.message.index', 'uses' => 'MessagesController@actionIndex'));

    Route::group(array('middleware' => array(MessageMiddleware::class)), function () {
        Route::post('/m/{messageId}/delete', array('as' => 'message.delete', 'uses' => 'Message\DeleteController@actionIndex'));
    });

    Route::group(array('middleware' => array(ConversationMiddleware::class), 'prefix' => 'c', 'as' => 'conversation.'), function () {
        Route::post('/{conversationId}/text/send', array('as' => 'message.send.text', 'uses' => 'Send\TextController@actionIndex'));
        Route::post('/{conversationId}/audio/send', array('as' => 'message.send.audio', 'uses' => 'Send\AudioController@actionIndex'));
        Route::post('/{conversationId}/attachment/send', array('as' => 'message.send.attachment', 'uses' => 'Send\AttachmentController@actionIndex'));
        Route::post('/{conversationId}/record/send', array('as' => 'message.send.record', 'uses' => 'Send\RecordController@actionIndex'));
        Route::post('/{conversationId}/product/send', array('as' => 'message.send.product', 'uses' => 'Send\ProductController@actionIndex'));
    });
});

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'store', 'as' => 'store.'), function () {
    Route::group(array('prefix' => 'chat', 'as' => 'chat.'), function () {
        Route::get('/conversations', array('as' => 'conversation.index', 'uses' => 'Store\ConversationsController@actionIndex'));
        Route::post('/create', array('as' => 'conversation.create', 'uses' => 'Store\CreateController@actionIndex'));
        Route::post('/conversation/get', array('as' => 'conversation.get', 'uses' => 'Store\ConversationController@actionIndex'));

        Route::group(array('middleware' => array(ConversationMiddleware::class), 'prefix' => 'c', 'as' => 'conversation.'), function () {
            Route::post('/{conversationId}/text/send', array('as' => 'message.send.text', 'uses' => 'Store\Send\TextController@actionIndex'));
            Route::post('/{conversationId}/audio/send', array('as' => 'message.send.audio', 'uses' => 'Store\Send\AudioController@actionIndex'));
            Route::post('/{conversationId}/attachment/send', array('as' => 'message.send.attachment', 'uses' => 'Store\Send\AttachmentController@actionIndex'));
            Route::post('/{conversationId}/record/send', array('as' => 'message.send.record', 'uses' => 'Store\Send\RecordController@actionIndex'));
            Route::post('/{conversationId}/product/send', array('as' => 'message.send.product', 'uses' => 'Store\Send\ProductController@actionIndex'));
        });
    });
});
