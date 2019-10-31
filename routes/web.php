<?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/store', 'HomeController@store')->name('store');
Route::get('/products', 'ProductController@index')->name('products.index');
Route::get('/add-to-cart/{product}', 'ProductController@addToCart')->name('cart.add');
Route::get("/shopping-cart", "ProductController@shopCart")->name('cart.show');

Route::patch('/update-cart', 'ProductController@update')->name('cart.update');;
 
Route::delete('/remove-from-cart', 'ProductController@remove')->name('cart.remove');;

Route::get("/checkout/{amount}", "ProductController@checkout")->name('cart.checkout');

Route::post("/charge", "ProductController@charge")->name('cart.charge');

Route::get("/orders", "OrderController@index")->name("order.index");