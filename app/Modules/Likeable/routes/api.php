<?php

use App\Modules\Category\Middleware\CategoryMiddleware;
use App\Modules\Store\Middleware\StoreMiddleware;
use App\Modules\Product\Middleware\ProductMiddleware;
use App\Modules\Feed\Http\Middleware\FeedMiddleware;

Route::group(array('middleware' => array('auth:api'), 'as' => 'likeable.'), function () {
    Route::post('/category/{categoryId}/like', array('as' => 'category.like', 'uses' => 'CategoryController@actionLike'))->middleware(CategoryMiddleware::class);
    Route::post('/category/{categoryId}/unlike', array('as' => 'category.unlike', 'uses' => 'CategoryController@actionUnlike'))->middleware(CategoryMiddleware::class);

    Route::post('/store/{storeId}/like', array('as' => 'store.like', 'uses' => 'StoreController@actionLike'))->middleware(StoreMiddleware::class);
    Route::post('/store/{storeId}/unlike', array('as' => 'store.unlike', 'uses' => 'StoreController@actionUnlike'))->middleware(StoreMiddleware::class);

    Route::post('/product/{productId}/like', array('as' => 'product.like', 'uses' => 'ProductController@actionLike'))->middleware(ProductMiddleware::class);
    Route::post('/product/{productId}/unlike', array('as' => 'product.unlike', 'uses' => 'ProductController@actionUnlike'))->middleware(ProductMiddleware::class);

    Route::post('/feed/{feedId}/like', array('as' => 'feed.like', 'uses' => 'FeedController@actionLike'))->middleware(FeedMiddleware::class);
    Route::post('/feed/{feedId}/unlike', array('as' => 'feed.unlike', 'uses' => 'FeedController@actionUnlike'))->middleware(FeedMiddleware::class);

    Route::get('/likeable/categories', array('as' => 'category', 'uses' => 'CategoriesController@actionIndex'));
    Route::get('/likeable/stores', array('as' => 'store', 'uses' => 'StoresController@actionIndex'));
    Route::get('/likeable/products', array('as' => 'product', 'uses' => 'ProductsController@actionIndex'));
});

Route::group(array('middleware' => array('auth:api'), 'prefix' => 'store', 'as' => 'store.'), function () {
    Route::group(array('as' => 'likeable.'), function () {
        Route::post('/feed/{feedId}/like', array('as' => 'feed.like', 'uses' => 'FeedController@actionLike'))->middleware(FeedMiddleware::class);
        Route::post('/feed/{feedId}/unlike', array('as' => 'feed.unlike', 'uses' => 'FeedController@actionUnlike'))->middleware(FeedMiddleware::class);
    });
});
