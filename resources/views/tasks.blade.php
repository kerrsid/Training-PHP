@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-sm-offset-2 col-sm-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                New Task
            </div>
            @include('partials/note-form')

            <!-- Current Tasks -->
            @if (count($tasks) > 0)
            <div class="panel panel-default">
                <div class="panel-heading">
                    Current Tasks
                </div>

                <div class="panel-body">
                    <table class="table table-striped task-table">
                        <thead>
                            <th>Task</th>
                            <th>&nbsp;</th>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                            <tr>
                                <td class="table-text">
                                    <div>{{ $task->name }}</div>
                                </td>

                                <!-- Task Delete Button -->
                                <td>
                                    <!-- Edit Button -->
                                    <form action="{{ route('task.edit', ['id' => $task->id]) }}" method='POST'>
                                        {{ csrf_field() }}
                                        {{ method_field('PUT') }}

                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-btn fa-edit"></i>Edit
                                        </button>
                                    </form>
                                </td>

                                <td>
                                    <form action="{{ route('task.delete', ['id' => $task->id]) }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-btn fa-trash"></i>Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
            @endif
        </div>
    </div>
</div>
@endsection

