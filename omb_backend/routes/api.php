<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', 'LoginController@getLogin');

Route::get('/users/search', 'UsersController@search')->middleware('token');
Route::apiResource('/users', 'UsersController')->middleware('token');

Route::apiResource('/questions', 'QuestionsController')->middleware('token');
Route::apiResource('/types', 'ComplaintTypesController')->middleware('token');

Route::post('/complaints/review/{id}', 'ComplaintsController@setReview')->middleware('token');
Route::post('/complaints/statement/{id}', 'ComplaintsController@setStatement')->middleware('token');
Route::post('/complaints/close/{id}', 'ComplaintsController@setClosing')->middleware('token');
Route::get('/complaints/resolution', 'ComplaintsController@getResolutions')->middleware('token');
Route::get('/complaints/report', 'ComplaintsController@getReport')->middleware('token');
Route::apiResource('/complaints', 'ComplaintsController')->middleware('token');

Route::get('/tracings/search/{id}', 'TracingController@search')->middleware('token');
Route::apiResource('/tracings', 'TracingController')->middleware('token');

Route::get('/file/{id}', 'CaseFileController@getFiles')->middleware('token');
Route::post('/file', 'CaseFileController@store')->middleware('token');
Route::delete('/file/{id}', 'CaseFileController@destroy')->middleware('token');
Route::get('/file/download/{filename}', 'CaseFileController@download');

Route::get('/survey/{id}', 'SurveyController@getSurvey')->middleware('token');
Route::post('/survey/{id}', 'SurveyController@setSurvey')->middleware('token');
