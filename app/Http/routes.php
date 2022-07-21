<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

use App\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\TaskController;

Route::group(['middleware' => ['web']], function () {

   Route::get('/', 'TaskController@index');

   Route::post('/task', 'TaskController@add');

   Route::delete('/task/{id}', 'TaskController@remove');

});
