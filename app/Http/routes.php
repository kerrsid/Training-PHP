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

$router = app("Illuminate\Routing\Router");

$router->group(['middleware' => ['web']], function () use ($router) {
   $ctrl = 'TaskController';
   $router->get('/', "{$ctrl}@index")->name('task.index');
   $router->post('/task/add', "{$ctrl}@add")->name('task.add');
   $router->post('/task/status/{id}', "{$ctrl}@selectStatus")->name('task.status');
   $router->put('/task/update/{id}', "{$ctrl}@update")->name('task.update');
   $router->delete('/task/delete/{id}', "{$ctrl}@remove")->name('task.delete');
});