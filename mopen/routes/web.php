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
Route::post('/home', 'HomeController@like')->name('like');
Route::resource('/home/coupon', 'CouponController');
Route::get('/home/coupon/create/upload', 'CouponController@upload')->name('listUploadPage');
Route::post('/home/coupon/create/upload', 'CouponController@uploadFile')->name("listUpload");
