<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Task;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index(){
        return view('tasks', [
            'tasks' => Task::orderBy('created_at', 'asc')->get(),
        ]);
    }

    public function taskDetails($id){
        return view('task', [
            'task' => Task::find($id),
        ]);
    }

    public function add(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'note' => 'max:255',
        ]);

        if ($validator->fails()) {
            return redirect(route('task.index'))
                ->withInput()
                ->withErrors($validator);
        }

        $task = new Task;
        $task->name = $request->name;
        $task->note = $request->note;
        $task->save();

        return redirect(route('task.index'));
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'note' => 'max:255',
        ]);

        if ($validator->fails()) {
            return redirect(route('task.index'))
                ->withInput()
                ->withErrors($validator);
        }

        $task = Task::find($id);

        if (strcmp($task->name, $request->name) != 0 || strcmp($task->note, $request->note) != 0) {
            $task->name = $request->name;
            $task->note = $request->note;
            $task->save();

            return redirect(route('task.index'));
        }

        return redirect(route('task.index'))->withInput()->withErrors("No changes detected! ");
    }

    public function selectStatus($id){
        $task = Task::find($id);

        if ($task->complete == true) {
            $task->complete = false;
        } else {
            $task->complete = true;
        }

        $task->save();

        return redirect(route('task.index'));
    }


    public function remove($id){
        Task::findOrFail($id)->delete();

        return redirect(route('task.index'));
    }
}
