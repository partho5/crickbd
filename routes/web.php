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

Route::get('/', 'HomepageController@index');



Route::resource('/match', 'MatchController');

Route::get('/match/player/add', 'MatchController@addPlayers');
Route::get('/match/admin/panel', 'MatchController@showAdminPanel');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
