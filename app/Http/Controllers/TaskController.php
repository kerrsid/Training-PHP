<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index()
    {
        return view('tasks', [
            'tasks' => Task::orderBy('created_at', 'asc')->get()
        ]);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'notes' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return redirect('/')
                ->withInput()
                ->withErrors($validator);
        }
        $task = new Task;
        $task->name = $request->name;
        $task->notes = $request->notes;
        if($task->save()){
            return redirect()->back()->with(['message' => 'Task '.$task->name.' saved successfully!', 'error' => false]);
        } else {
            return redirect()->back()->with(['message' => 'Error while trying to save task!', 'error' => true]);
        };
    }

    public function edit($id,Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'notes' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return redirect('/')
                ->withInput()
                ->withErrors($validator);
        }
        $task = Task::find($id);
        if(!isset($task)){
            return redirect('/'->previous() . '#modalHeader')->withErrors(['message', 'Error while trying to edit task. Task could not be found.']);
        }
        $task->name = $request->name;
        $task->notes = $request->notes;
        if($task->save()){
            return redirect()->back()->with(['message' => 'Task '.$task->name.' updated successfully!', 'error' => false]);
        } else {
            return redirect()->back()->with(['message' => 'Error while trying to update task!', 'error' => true]);
        };
    }

    public function delete($id)
    {
        $task = Task::find($id);
        if(isset($task) && $task->delete()){
            return redirect()->back()->with(['message' => 'Task '. $task->name .' deleted successfully!','error' => false]);
        } else {
            return redirect()->back()->with(['message' => 'Task failed to be deleted!','error' => true]);                  
        }
    }
}
