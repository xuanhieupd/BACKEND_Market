<?php

use App\Modules\Attachment\Middleware\AttachmentMiddleware;

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'attachment', 'as' => 'attachment.'), function () {
    Route::get('/{fileName}.{attachmentId}', array('as' => 'view', 'uses' => 'IndexController@actionIndex'));
    Route::post('/upload', array('as' => 'upload', 'uses' => 'UploadController@actionIndex'));
});
