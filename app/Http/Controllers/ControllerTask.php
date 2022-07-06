<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;

class ControllerTask extends Controller
{
    public function list()
    {
        return view('tasks', [
            'tasks' => Task::orderBy('created_at', 'asc')->get()
        ]);
    }

    public function addEditRemove($id,$argument,Request $request)
    {
        switch($argument){
            case 'edit' :
                $validator = Validator::make($request->all(), [
                    'name' => 'required|max:255',
                    'notes' => 'required|max:255',
                ]);
        
                if ($validator->fails()) {
                    return redirect('/')
                        ->withInput()
                        ->withErrors($validator);
                }
                if($id == 0){
                    $task = new Task;
                    $message = 'Task placeholder saved successfully!';
                } else {
                    $task = Task::find($id);
                    $message = 'Task placeholder updated successfully!';
                }
                if(!isset($task)){
                    return redirect('/'->previous() . '#modalHeader')->withErrors(['message', 'Error while trying to edit task. Task could not be found.']);
                }
                $task->name = $request->name;
                $task->notes = $request->notes;
                $message = str_replace('placeholder', $task->name, $message);
                if($task->save()){
                    return redirect()->back()->with(['message' => $message, 'error' => false]);
                } else {
                    return redirect()->back()->with(['message' => 'Error while trying to save task!', 'error' => true]);
                };
        
            break;
            case 'delete' :
                $task = Task::find($id);
                if(isset($task)){
                    $task->delete();
                    return redirect()->back()->with(['message' => 'Task '. $task->name .' deleted successfully!','error' => false]);
                } else {
                    return redirect()->back()->with(['message' => 'Task failed to be deleted!','error' => true]);                  
                }
            break;
        }
    }
}
