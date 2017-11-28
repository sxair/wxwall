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


Route::get('/{type?}','WallController@showWall');
Route::post('/addwall','WallController@addWall');
Route::get('/ajwall/{type}/{last}','WallController@ajWall');
Route::get('/agree/{id}/{add}','AgreeController@agree');

Route::post('/gimage','WallController@image');

Route::get('/showreply/{id}','ReplyController@showReply');
Route::post('/addreply','ReplyController@addReply');
