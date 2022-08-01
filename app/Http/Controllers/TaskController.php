<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Task;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App;

class TaskController extends Controller
{
    public function index()
    {
        return view('tasks', [
            'tasks' => Task::orderBy('created_at', 'asc')->get(),
        ]);
    }

    public function taskDetails($id)
    {
        $task = Task::find($id);

        if ($task) {
            return view('task', [
                'task' => $task,
            ]);
        }

        return redirect(route('task.index'))->withInput()->withErrors("This task does not exist! ");
    }

    public function add(Request $request)
    {
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

    public function update(Request $request, $id)
    {
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

    public function selectStatus($id)
    {
        $task = Task::find($id);

        if ($task->complete == true) {
            $task->complete = false;
        } else {
            $task->complete = true;
        }

        $task->save();

        return redirect(route('task.index'));
    }

    public function remove($id)
    {
        Task::findOrFail($id)->delete();

        $filePath = public_path('assets/files/' . $id);

        if (File::isDirectory($filePath)) {
            File::deleteDirectory($filePath);
        }

        return redirect(route('task.index'));
    }

    //Upload file
    public function uploadFile(Request $request, $id)
    {   
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:pdf,doc,docx,txt|max:1024',
        ]);

        if ($validator->fails()) {
            return redirect(route('task.details', $id))
                ->withInput()
                ->withErrors($validator);
        }

        $document = $request->file('file');
        $filePath = public_path('assets/files/' . $id);

        if (!File::isDirectory($filePath)) {
            File::makeDirectory($filePath, 0777, true, true);
        }

        $document->move($filePath, $document->getClientOriginalName());

        $task = Task::find($id);
        $task->file_path = 'assets/files/' . $id . '/' . $document->getClientOriginalName();

        $task->save();

        return redirect(route('task.details', $id));
    }

    //Delete file
    public function deleteFile($id)
    {
        $task = Task::find($id);

        unlink(public_path($task->file_path));

        $task = Task::find($id);
        $task->file_path = '';
        $task->save();

        return redirect(route('task.details', $id));
    }

    //Download file
    public function downloadFile($id)
    {
        $task = Task::find($id);

        if (file_exists($task->file_path)) {
            return response()->download(public_path($task->file_path));
        }

        return redirect(route('task.details', $id))
            ->withInput()
            ->withErrors("The file does not exist!");
    }

    //Preview
    public function showFile($id)
    {
        $task = Task::find($id);

        if (file_exists($task->file_path)) {
            return response()->file($task->file_path);
        }

        return redirect(route('task.details', $id))
            ->withInput()
            ->withErrors("The file does not exist!");
    }

    //Rename file
    public function renameFile(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'fileName' => [
                'required',
                'max:50',
            ],
        ]);

        if ($validator->fails()) {
            return redirect(route('task.details', $id))
                ->withInput()
                ->withErrors($validator);
        }

        $task = Task::find($id);

        $originalFileName = basename($task->file_path);
        $originalFileName = substr($originalFileName, 0, strrpos($originalFileName, '.'));

        if ($originalFileName === rtrim($request->fileName)) {
            return redirect(route('task.details', $id))
                ->withInput()
                ->withErrors("The file name has not been changed!");
        }

        $fileExtension = pathinfo($task->file_path, PATHINFO_EXTENSION);
        $newPath = dirname($task->file_path) . '/' . rtrim($request->fileName) . '.' . $fileExtension;

        rename(public_path($task->file_path), public_path($newPath));

        $task->file_path = $newPath;
        $task->save();

        return redirect(route('task.details', $id));
    }
}
