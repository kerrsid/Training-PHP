<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\File;
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
        $files = File::where('task_id', $id)->get();
        // nu e cea mai eleganta solutie dar e cea mai simpla pentru localizarea orei (in DB sunt tot UTC)
        foreach($files as $file){
            $file->created_at = Carbon::parse($file->created_at)->setTimeZone('Europe/Bucharest');
        }
        return view('details', [
                'task' => Task::find($id),
                'files' => $files,
            ]
        );
    }

    public function addFile($id, Request $request)
    {
        if($request->file('file')){
            $file = $request->file('file');
            $fileName = Carbon::now()->toDateString() . '_' . $id . '_' . $file->getClientOriginalName();
            if(Storage::disk('taskFiles')->exists($fileName)){
                $ext = pathinfo($fileName);
                $fileName = basename($ext['filename'] . '.copy.' . $ext['extension']); 
            }
            if(Storage::disk('taskFiles')->put($fileName, file_get_contents($file))){
                $dbFile = new File;
                $dbFile->task_id = $id;
                $dbFile->filename = $fileName;
                $dbFile->path = Storage::disk('taskFiles')->getDriver()->getAdapter()->getPathPrefix('') . $fileName;
                $dbFile->updated_at = Carbon::now();
                if($dbFile->save()){
                    return back()->with(['message' => 'File '.$dbFile->filename.' saved succesfully!', 'error' => false]);
                } else {
                    return back()->with(['message' => 'File '.$dbFile->filename.' could not be saved!', 'error' => true]);
                };
            }
        }
    }

    public function downloadFile($id)
    {
        $file = File::find($id);
        if(!isset($file) || !Storage::disk('taskFiles')->exists($file->filename)){
            return back()->with(['message' => 'File '.$file->filename.' could not be found!', 'error' => true]);
        }
        return response()->download($file->path);
    }

    public function deleteFile($id)
    {
        $file = File::find($id);
        if(isset($file)){
            if(Storage::disk('taskFiles')->exists($file->filename) && isset($file)){
                Storage::disk('taskFiles')->delete($file->filename);
            }
            $file->delete();
        } else {
            return back()->with(['message' => 'File '.$file->filename.' could not be found!', 'error' => true]);
        }
        return back()->with(['message' => 'File '.$file->filename.' successfully deleted!', 'error' => false]);
    }

    public function editFile($id)
    {
        $file = File::find($id);
        $oldFile = clone $file;
        if(!isset($file)){
            return back()->with(['message' => 'File '.$file->filename.' could not be found!', 'error' => true]);
        }
        $ext = pathinfo($file->filename)['extension'];
        $file->filename = pathinfo(trim($_POST['name']))['filename'] . '.' . $ext;
        $file->path = Storage::disk('taskFiles')->getDriver()->getAdapter()->getPathPrefix('') . $file->filename;
        if($file != $oldFile){
            if($file->save()){
                Storage::disk('taskFiles')->move($oldFile->filename, $file->filename);
                return back()->with(['message' => 'File '.$file->filename.' successfully updated!', 'error' => false]);
            };
        } else {
            return back()->with(['message' => 'File '.$file->filename.' successfully updated!', 'error' => false]);
        }
    }
}
