<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
 */

Route::get('/', function () {
    return view('welcome');
});

Route::get('cache-clear', function () {
	Artisan::call('cache:clear');
	Artisan::call('config:clear');
	Artisan::call('view:clear');
	Artisan::call('route:clear');

	return 'cleared';
});


Route::get('home', 'HomeController@index');

Route::get('testcash', 'CashfreeController@index');
Route::get('testcashsubmit', 'CashfreeController@testcashsubmit');
Route::get('cash_response', 'CashfreeController@response');
Route::get('leaderboard', 'WebSocketController@onMessage');    