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

Route::get('/', function () {
    $customer = \App\Modules\Customer\Models\Entities\Customer::first();
    echo $customer->balance;
    die;
    $user = App\Modules\User\Models\Entities\User::first();

    $product1Item = new App\Modules\Order\Models\Entities\Item();
    $product2Item = new App\Modules\Order\Models\Entities\Item();
    $products = collect(array($product1Item, $product2Item));

    $cart = app(Cart::class);
    foreach ($products as $product) {
        $cart->addItem($product, 10);
    }

//    echo $cart->getTotal($customer) . '\n';
//    print_r($cart);
//    die;

    $customer->deposit($cart->getTotal($customer));

    return view('welcome');
});
