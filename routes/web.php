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

Route::group(array('prefix' => 'share', 'as' => 'share.', 'namespace' => '\App\Modules\Share\ControllerPublic'), function () {
    Route::get('/store/{storeId}', array('as' => 'store', 'uses' => 'StoreController@actionIndex'));
});
