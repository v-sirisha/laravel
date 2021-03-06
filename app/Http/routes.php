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

/*Route::get('/', 'EventController@welcome');*/

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
Route::get('contact',function(){
	return view('contact');
});
/* cart routes*/
Route::get('cartview','CartController@showCart');
Route::get('cart/destroy', 'CartController@destroy');
Route::get('/cart/{productid}', 'CartController@cart');
Route::get('/cart/remove/{productid}', 'CartController@itemRemove');
Route::get('/cart/update/{productid}/{quantity}', 'CartController@updateCart');
Route::get('sendEmail','EventController@sendEmailReminder');
Route::get('/order','EventController@placeOrder');
Route::get('/order-history/{id}','EventController@getOrderHistory');
Route::get('/order-details/{id}','EventController@getOrderDetails');
Route::get('/store-cart','CartController@storeCart');
Route::get('/destroy-cart','CartController@destroyCart');
Route::get('/user-account/{id}','EventController@getUserDetails');
Route::get('/user/update/{id}','EventController@updateUserDetails');
Route::get('/user/updatepassword/{id}','EventController@changePassword');
View::share('cartCount', Cart::count());

/* Reporting Routes */

Route::get('/','ReportingController@index');
Route::post('/store-data/{platform}','ReportingController@storeData');
Route::get('/exportdata-excel','ReportingController@getFinalPR_Report');
Route::post('/importToData','ReportingController@importToDB');
Route::get('/update/{type}','ReportingController@updateon_screen');
Route::get('/download-excel/{type}','ReportingController@download_miss_data_excel');
Route::get('get-lastmodified-date/{pt_name}','ReportingController@getEndDate');
Route::get('downloads/{type}','ReportingController@defaultTemplates');
Route::get('/update-row','ReportingController@onScreenAction');