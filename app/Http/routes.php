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

$router = app("Illuminate\Routing\Router");

$router->group(['middleware' => ['web']], function () use ($router) {
    $ctrl = 'TaskController';
    // Show task dashboard
    $router->get('/',                             "{$ctrl}@index")->name('task.index');
    // Add task
    $router->post('/task/add',                    "{$ctrl}@add")->name('task.add');
    // Edit task
    $router->patch('/task/edit/{id}',             "{$ctrl}@edit")->name('task.edit');
    // Delete task
    $router->delete('/task/delete/{id}',          "{$ctrl}@delete")->name('task.delete');
    // Change task status
    $router->post('/task/statusChange',       "{$ctrl}@statusChange")->name('task.statusChange');
    // Open task details page
    $router->get('/task/details/{id}',       "{$ctrl}@details")->name('task.details');
    // Add file for task
    $router->post('/task/addFile/{id}',      "{$ctrl}@addFile")->name('task.addFile');
});