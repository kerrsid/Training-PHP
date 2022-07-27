@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-sm-offset-2 col-sm-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                New Task
            </div>
            <form action="{{ route('task.add') }}" method="POST" class="form-horizontal">
                {{ csrf_field() }}
                {{ method_field('POST')}}

                <div class="panel-body">
                    @include('common.errors')
                    <div class="form-group">
                        @include('partials/note-form')

                        <div class="col-sm-offset-3 col-sm-6">
                            <button type="submit" class="btn btn-default">
                                <i class="fa fa-btn fa-plus"></i>&ensp;Add Task
                            </button>
                        </div>
                    </div>
            </form>
            <!-- Current Tasks -->
            @if (count($tasks) > 0)
            <div class="panel panel-default">
                <div class="panel-heading">
                    Current Tasks
                </div>

                <div class="panel-body">
                    <table class="table table-striped task-table  table-hover">
                        <thead>
                            <th>Task</th>
                            <th>&nbsp;</th>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                            <tr>
                        
                                <td class="table-text-field">
                                 @if ( $task->complete == 1)
                                    <div style='text-decoration: line-through;'> {{ $task->name }} </div>
                                 @else
                                    <div>{{ $task->name }}</div>
                                 @endif

                                </td>

                                <!-- Task Delete Button -->
                                <td class="buttons-table-field">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-{{ $task->id }}">
                                        <i class="fa fa-btn fa-edit"></i>
                                    </button>

                                    <form action="{{ route('task.delete', $task->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-btn fa-trash"></i>
                                        </button>
                                    </form>

                                </td>
                                <td>
                                    <div class="form-group form-check">
                                     <form action="{{ route('task.complete', $task->id) }}" id="form-status-{{$task->id}}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('PATCH') }}

                                         @if($task->complete == 1) 
                                            <input type="checkbox" class="form-check-input" onclick="submitStatus({{$task->id}})" checked>
                                            <label class="form-check-label" for="status">Done</label>
                                        @else
                                            <input type="checkbox" class="form-check-input" onclick="submitStatus({{$task->id}})">
                                            <label class="form-check-label" for="status">Pending</label>
                                        @endif

                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="modal-{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalCenterTitle">Edit panel</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('task.update', $task->id) }}" id="form-modal-{{ $task->id }}" method="POST">
                                                {{ csrf_field() }}
                                                {{ method_field('PUT')}}
                                                @include('partials/note-form')
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary" onclick="form_submit({{ $task->id }})">Save changes</button>
                                        </div>
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

<script>
    function form_submit(id) {
        document.getElementById("form-modal-" + id).submit();
    }

    function submitStatus(id){
       document.getElementById("form-status-" + id).submit();
    }
</script>

@endsection

