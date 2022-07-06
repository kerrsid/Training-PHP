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

use App\Models\Task;
use Illuminate\Http\Request;

Route::group(['middleware' => ['web']], function () {
    // Show task dashboard
    Route::get('/', 'ControllerTask@list');
    // Add task
    Route::post('/task/{id}/{argument}', 'ControllerTask@addEditRemove');
    // Edit task
    Route::patch('/task/{id}/{argument}', 'ControllerTask@addEditRemove');
    // Delete task
    Route::delete('/task/{id}/{argument}', 'ControllerTask@addEditRemove');
});
