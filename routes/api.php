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

Route::get('/', function () {
    return;
});

Route::apiResources([
    'sessions' => 'SessionController',
    'actions' => 'ActionController',
    'variables' => 'VariableController'
]);

Route::get('sessions/{session}/actions', 'SessionController@actions')->name('sessions.actions');
Route::get('sessions/{session}/variables', 'SessionController@variables')->name('sessions.variables');
Route::get('actions/{action}/variables', 'ActionController@variables')->name('actions.variables');

Route::get('stats/details/{session}', 'StatsController@index')->name('stats.index');
