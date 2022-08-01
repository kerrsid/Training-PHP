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
   $router->get('/task/{id}', "{$ctrl}@taskDetails")->name('task.details');
   $router->post('/task/add', "{$ctrl}@add")->name('task.add');
   $router->post('/task/status/{id}', "{$ctrl}@selectStatus")->name('task.status');
   $router->put('/task/update/{id}', "{$ctrl}@update")->name('task.update');
   $router->delete('/task/delete/{id}', "{$ctrl}@remove")->name('task.delete');

   $router->get('/file/download/{id}', "{$ctrl}@downloadFile")->name('file.download');
   $router->get('/file/preview/{id}', "{$ctrl}@showFile")->name('file.preview');
   $router->post('/file/upload/{id}', "{$ctrl}@uploadFile")->name('file.upload');
   $router->put('/file/rename/{id}', "{$ctrl}@renameFile")->name('file.rename');
   $router->delete('/file/delete/{id}', "{$ctrl}@deleteFile")->name('file.delete');
});