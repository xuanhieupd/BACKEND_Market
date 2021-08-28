<?php

use Illuminate\Support\Facades\Route;
use Bavix\Wallet\Objects\Cart;

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::group(array('prefix' => 's', 'as' => 's.', 'namespace' => '\App\Modules\ShortUrl\ControllerPublic'), function () {
    Route::get('/{code}', array('as' => 'redirect', 'uses' => 'RedirectController@actionIndex'));

    Route::get('/store/{modelId}', array('as' => 'redirect', 'uses' => 'StoreController@actionIndex'));
    Route::get('/product/{modelId}', array('as' => 'redirect', 'uses' => 'ProductController@actionIndex'));
    Route::get('/conversation/{modelId}', array('as' => 'redirect', 'uses' => 'ConversationController@actionIndex'));
    Route::get('/user/{modelId}', array('as' => 'redirect', 'uses' => 'UserController@actionIndex'));
    Route::get('/feed/{modelId}', array('as' => 'redirect', 'uses' => 'FeedController@actionIndex'));
});
