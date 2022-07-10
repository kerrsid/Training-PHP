<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Files;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public function statusChange()
    {
        $error = false;
        $message = '';
        $data = [];
        if(isset($_POST['id']) && trim($_POST['id'] != '')){
            $task = Task::find(trim($_POST['id']));
            if(isset($_POST['status']) && trim($_POST['status'] != '')){
                $task->status = trim($_POST['status']);
                $task->save();
            } else {
                $message = 'Error! Something went wrong when changing status!';
                $error = true;
            }
        } else {
            $message = 'Error! Invalid task ID.';
            $error = true;
        }
        echo json_encode([
            'message' => $message,
            'error' => $error,
        ]);
        exit;
    }

    public function details($id)
    {
        return view('details', ['task' => Task::find($id)]);
    }

    public function addFile($id, Request $request)
    {
        if($request->file('file')){
            $file = $request->file('file');
            $fileName = Carbon::now()->toDateString() . '_' . $file->getClientOriginalName();
            if(Storage::disk('taskFiles')->put($fileName, file_get_contents($file))){
                //TODO retrieve saved files based on task id
                DB::table('files')->insert([
                    'task_id' => $id,
                    'path' => Storage::disk('taskFiles')->getDriver()->getAdapter()->getPathPrefix('') . $fileName,
                ]);
                //TODO add feedback on file upload

                //TODO move commented code to dedicated download function
                // $dbFile = DB::table('files')->select([
                //     'id',
                //     'task_id',
                //     'path',
                // ])
                // ->where('task_id', $id)
                // ->first();
                // return response()->download($dbFile->path);

                //TODO add deletion (maybe check for duplicates)
            }
        }
    }
}
