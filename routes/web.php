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

Route::get('/match/{id}/addplayer', 'MatchController@addPlayers')->middleware('auth','checkCreator','isPlayerAdded');
Route::get('/mygames', 'AdminCommandController@showAdminPanel')->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/match/{id}/addplayer','MatchController@storePlayers')->middleware('auth','checkCreator','isPlayerAdded');
Route::get('/matchpanel/{id}','AdminCommandController@addInnings')->middleware('auth','checkCreator');
Route::get('/details/{id}','MatchController@matchDetails');
Route::post('/getmatchdata/match/settoss/{id}','AdminCommandController@insertTossData')->middleware('auth','checkCreator');
Route::get('/view/{id}','MatchController@viewMatch');
Route::post('/getmatchdata/match/setinnings/{id}','AdminCommandController@initializeInnings')->middleware('auth','checkCreator','createValidSession');
Route::post('/getmatchdata/match/endinnings/{id}','AdminCommandController@endInnings')->middleware('auth','checkCreator');
Route::post('/getmatchdata/match/addnewball/{id}','AdminCommandController@addNewBall')->middleware('auth','checkCreator');
Route::post('/getmatchdata/match/deletelast/{id}','AdminCommandController@deletelastBall')->middleware('auth','checkCreator');

Route::get('/getmatchdata/{id}','AdminCommandController@getMatchDataApi');
Route::get('/getresumematchdata/{id}','AdminCommandController@getResumeDataApi');

Route::get('/mygames/edit/{id}','AdminCommandController@showEditPage')->middleware('auth','checkCreator');
Route::post('/mygames/edit/{id}','AdminCommandController@editMatchData')->middleware('auth','checkCreator');

Route::get('/mygames/edit_players/{id}','AdminCommandController@showEditPlayerPage')->middleware('auth','checkCreator');
Route::post('/mygames/edit_players/{id}','AdminCommandController@editMatchPlayers')->middleware('auth','checkCreator');

Route::get('/scoreboard/{id}','MatchController@showScoreBoard');

