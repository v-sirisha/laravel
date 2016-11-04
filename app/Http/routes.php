<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');
Route::get('/addproduct',function(){
	return view('events.add_product');
});
Route::post('create_product','EventController@create');
Route::get('show/{key}','EventController@show_products');
Route::get('getprod/{val}','EventController@getRecords');
Route::get('/detail/{id}', 'EventController@product_details');
Route::get('/checkout_page','EventController@checkoutCart');
Route::get('404',function(){
	return view('404');
});
/* cart routes*/
Route::get('cartview','CartController@showCart');
Route::get('cart/destroy', 'CartController@destroy');
Route::get('/cart/{productid}', 'CartController@cart');
Route::get('/cart/remove/{productid}', 'CartController@itemRemove');
Route::get('/cart/update/{productid}/{quantity}', 'CartController@updateCart');

View::share('cartCount', Cart::count());
