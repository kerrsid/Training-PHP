@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Task Details
                </div>
            <div class="panel-body">
                <!-- Task Details Form -->
                <form action="{{ route('task.addFile' , $task->id) }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row" style="display:flex;align-items:center;flex-direction:column;gap:15px;">
                        <div class="col-sm-6">
                            <input type="text" value="{{ $task->name }}" class="form-control" readonly>
                        </div>
                        <div class="col-sm-6">
                            <textarea name="" id="" class="form-control" style="resize:none;" cols="30" rows="10" readonly>{{ $task->notes }}</textarea>
                        </div>
                        <div class="col-sm-6">
                            <input type="file" style="display:none;" id="fileInput" name="file"/>
                            <label for="fileInput">
                                <button type="button" id="fileBtn">
                                    <i class="fa fa-upload"></i>
                                </button>
                                <span id="fileInputLabel">No files selected</span>
                            </label>
                            <input type="submit" value="Upload" id="uploadBtn" class="btn btn-success pull-right hidden">
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
@endsection