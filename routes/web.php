<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;

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
Route::get('/user/create','App\Http\Controllers\AuthController@createUser');
Route::get('/check','App\Http\Controllers\ItemController@checkExpired');
Route::group(['middleware' => ['ifLogged']], function () {
    Route::get('/','App\Http\Controllers\AuthController@index');
    Route::post('/doLogin','App\Http\Controllers\AuthController@doLogin');
    Route::get('/doLogout','App\Http\Controllers\AuthController@doLogout');
});

Route::group(['middleware' => ['authLogin']], function () {
    Route::get('/dashboard','App\Http\Controllers\HomeController@index');

    //Item
    Route::get('/item','App\Http\Controllers\ItemController@index');
    Route::get('/item/load','App\Http\Controllers\ItemController@loadData');
    Route::get('/item/create','App\Http\Controllers\ItemController@createItem');
    Route::post('/item/insert','App\Http\Controllers\ItemController@insertItem');
    Route::get('/item/edit/{id}','App\Http\Controllers\ItemController@editItem');
    Route::post('/item/update/{id}','App\Http\Controllers\ItemController@updateItem');
    Route::get('/item/delete/{id}','App\Http\Controllers\ItemController@deleteItem');
    Route::get('/item/get/{id}','App\Http\Controllers\ItemController@getItem');

    Route::get('/transaksi','App\Http\Controllers\TransaksiController@index');
    Route::post('/transaksi/insert','App\Http\Controllers\TransaksiController@addTransaksi');
    Route::get('/transaksi/list','App\Http\Controllers\TransaksiController@list');
    Route::get('/transaksi/load','App\Http\Controllers\TransaksiController@loadData');
    Route::get('/transaksi/nota/{id}','App\Http\Controllers\TransaksiController@nota');
    Route::get('/transaksi/edit/{id}','App\Http\Controllers\TransaksiController@edit');
    Route::post('/transaksi/update/{id}','App\Http\Controllers\TransaksiController@update');
    Route::get('/transaksi/delete/{id}','App\Http\Controllers\TransaksiController@destroy');

    Route::get('/user','App\Http\Controllers\AuthController@user');
    Route::get('/user/load','App\Http\Controllers\AuthController@loadData');
    Route::post('/user/insert','App\Http\Controllers\AuthController@insertUser');
    Route::get('/user/edit/{id}','App\Http\Controllers\AuthController@editUser');
    Route::post('/user/update/{id}','App\Http\Controllers\AuthController@updateUser');
    Route::get('/user/delete/{id}','App\Http\Controllers\AuthController@destroyUser');
    Route::get('/user/status/{status}/{id}','App\Http\Controllers\AuthController@changeStatus');

    Route::get('/calculate/{id}/{type}','App\Http\Controllers\TransaksiController@calculateStok');
    Route::post('/kategori/add','App\Http\Controllers\ItemController@selectAdd');
    Route::get('/item/master/{id}','App\Http\Controllers\ItemController@item_master_detail');
});
