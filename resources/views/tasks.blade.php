@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    New Task
                </div>
                <div class="panel-body">
                    <!-- Display Validation Errors -->
                    @include('common.errors')
                    @if(session()->has('message') || session()->has('error'))
                    <div class='{{ session()->get("error") == true ? "alert alert-warning" : "alert alert-success" }}'>
                        {{ session()->get('message') }}
                    </div>
                    @endif
                    <!-- New Task Form -->
                    <form action="{{ route('task.add')}}" method="POST" class="form-horizontal">
                        <!-- Task Name -->
                        <div class="form-group">
                            <label for="task-name" class="col-sm-3 control-label">Title</label>

                            <div class="col-sm-6">
                                <input type="text" name="name" id="task-name" class="form-control" value="{{ old('task') }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="taskNotes" class="col-sm-3 control-label">Notes</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" style="resize:none" name="notes" id="taskNotes" cols="30" rows="10" required></textarea>
                            </div>
                        </div>
                        <!-- Add Task Button -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <button type="submit" class="btn btn-default pull-right">
                                    <i class="fa fa-btn fa-plus"></i>Add Task
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Current Tasks -->
            @if (count($tasks) > 0)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Current Tasks
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped task-table">
                            <thead>
                                <th style="white-space nowrap;">&nbsp;</th>
                                <th>Task</th>
                                <th>Notes</th>
                                <th style="white-space: nowrap;">&nbsp;</th>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $task)
                                    <tr class="{{ $task->status == 1 ? 'strikeout' : '' }}">
                                        <td class="strikeout">
                                            <div class="form-check" style="text-align:center;">
                                                
                                                <input type="checkbox" class="form-check-input taskStatus" name="task_status" data-id="{{ $task->id }}" {{ $task->status == 1 ? 'checked' : ''}} >
                                            </div>
                                        </td>
                                        <td class="table-text"><a href="{{ route('task.details', $task->id) }}"><div>{{ $task->name }}</div></a></td>
                                        <td class="table-text"><div>{{ strlen($task->notes) > 20 ? 
                                                substr($task->notes, 0, 20) . '...' : $task->notes }}</div></td>
                                        <!-- Task Delete Button -->
                                        <td class="d-flex">
                                            <form action="{{ route('task.delete', $task->id) }}" method="POST" class="m-2">
                                                {{ method_field('DELETE') }}
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                            <div class="m-2">
                                                <button class="btn btn-primary" data-toggle="modal" data-target="#myModal-{{ $task->id }}">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Modal -->
                                        <div class="modal fade" id="myModal-{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header" id="modalHeader">
                                                        @include('common.errors')
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="myModalLabel">Modal {{ $task->name }}</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('task.edit', $task->id) }}" method="POST" class="form-horizontal">
                                                        {{ method_field('PATCH') }}
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="task-name">Title</label>
                                                                        <input type="text" name="name" id="task-name" class="form-control" placeholder="Title" value="{{ $task->name }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="taskNotesModal">Notes</label>
                                                                        <textarea name="notes" aria-label="Notes" class="form-control" id="taskNotesModal" cols="10" rows="10" style="resize:none;" placeholder="Notes" required>{{ $task->notes }}</textarea>
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
@endsection
