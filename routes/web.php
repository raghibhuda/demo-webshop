<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/','WebShopController@index')->name('index');
Route::get('/cart','WebShopController@getAddedProductsFromCart')->name('cart');
Route::get('/cart-count','WebShopController@getCartItemCount')->name('getCartItemCount');
Route::post('/add-to-cart','WebShopController@addToCart')->name('addToCart');
Route::post('/remove-from-cart','WebShopController@removeFromCart')->name('removeFromCart');
Route::post('/place-order','WebShopController@placeOrder')->name('placeOrder');

