@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Task Details
                </div>
                <div class="panel-body">
                    @include('common.errors')
                    @if(session()->has('message') || session()->has('error'))
                    <div class='{{ session()->get("error") == true ? "alert alert-warning" : "alert alert-success" }}'>
                        {{ session()->get('message') }}
                    </div>
                    @endif
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
                    @if(count($files) > 0)
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped task-table">
                                    <thead>
                                        <th>File Name</th>
                                        <th>Date added</th>
                                        <th style="white-space: nowrap;">&nbsp;</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($files as $file)
                                            <tr>
                                                <td><a href="#" data-toggle="modal" data-target="#myModal-{{ $file->id }}">{{$file->filename}}</td>
                                                <td>{{$file->created_at}}</td>
                                                <td class="d-flex">
                                                    <div class="m-2">
                                                        <a href="{{ route('file.delete',  $file->id) }}">
                                                            <button class="btn btn-danger">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </a>
                                                    </div>
                                                    <div class="m-2">
                                                        <a href="{{ route('file.download',  $file->id) }}">
                                                            <button class="btn btn-primary">
                                                                <i class="fa fa-download"></i>
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- Modal -->
                                            <div class="modal fade" id="myModal-{{ $file->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header" id="modalHeader">
                                                            @include('common.errors')
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title" id="myModalLabel">File {{ $file->filename }}</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('file.edit', $file->id) }}" method="POST" class="form-horizontal">
                                                                {{ method_field('PATCH') }}
                                                                {{ csrf_field() }}
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label for="task-name">Filename</label>
                                                                            <input type="text" name="name" id="file-name" class="form-control" placeholder="Title" value="{{ $file->filename }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection