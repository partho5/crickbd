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

Route::resource('match','MatchController');

Route::get('/match/{id}/addplayer', 'MatchController@addPlayers')->middleware('auth','checkCreator');
Route::get('/mygames', 'AdminCommandController@showAdminPanel')->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/match/{id}/addplayer','MatchController@storePlayers')->middleware('auth','checkCreator');
Route::get('/matchpanel/{id}','AdminCommandController@addInnings')->middleware('auth','checkCreator');
Route::get('/details','MatchController@matchDetails');