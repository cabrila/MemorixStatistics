<?php

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

Route::get('/', function () {
    return;
});

Route::apiResources([
    'sessions' => 'SessionController',
    'actions' => 'ActionController',
    'variables' => 'VariableController',
]);

Route::get('sessions/{session}/actions', 'SessionController@actions')->name('sessions.actions');
Route::get('sessions/{session}/variables', 'SessionController@variables')->name('sessions.variables');
Route::get('actions/{action}/variables', 'ActionController@variables')->name('actions.variables');

Route::prefix('stats')->namespace('Stats')->group(function () {
    Route::get('counts', 'CountController@index')->name('stats.count');
    Route::post('counts', 'CountController@counts')->name('stats.count.counts');
    Route::get('users/login', 'UserController@login')->name('stats.user.login');
    Route::get('users/{user}', 'UserController@sessions')->name('stats.user.sessions');
});