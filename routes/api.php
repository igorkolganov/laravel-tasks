<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Task;

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


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('tasks', 'App\Http\Controllers\TasksController@index');
Route::get('task/{id}', 'App\Http\Controllers\TasksController@show');
Route::post('task', 'App\Http\Controllers\TasksController@store');
Route::post('task/{id}', 'App\Http\Controllers\TasksController@update');
Route::delete('task/{id}', 'App\Http\Controllers\TasksController@destroy');

//Route::resource('tasks', 'App\Http\Controllers\TasksController');
