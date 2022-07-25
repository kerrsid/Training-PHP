<div class="panel-body">
    <!-- Display Validation Errors -->
    @include('common.errors')

    <form action="{{ isset($task) ? route('task.update',['task' => $task]) :route('task.add') }}" method="POST" class="form-horizontal">
        {{ csrf_field() }}
        @if (isset($task))
        {{ method_field('PUT')}}
        @else
        {{ method_field('POST')}}
        @endif
        <!-- Task Name -->
        <div class="form-group">

            <div class=".container-sm ">
                <label for="task-name" class="col-sm-3 control-label">Task</label>
                <div class="col-sm-6">
                    <input type="text" name="name" id="task-name" class="form-control" value="{{ $task->name or old('add') }}">
                </div>
            </div>
            <br>
            <br>
            <br>
            <div class=".container-sm" style="margin-bottom: 5px;">
                <label for="task-note" class="col-sm-3 control-label">Note</label>
                <div class="col-sm-6">
                    <textarea class="form-control" name="note" id="task-note" rows="3">{{ $task->note or old('add') }}</textarea>
                </div>
            </div>
        </div>
        <!-- Add Task Button -->
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-6">
                <button type="submit" class="btn btn-default">
                    @if ( isset($task) )
                    <i class="fa fa-btn fa-edit"></i>Edit Task
                    @else
                    <i class="fa fa-btn fa-plus"></i>Add Task
                    @endif
                </button>
            </div>
            <!-- Cancel btn -->
            @if ( isset($task) )
            <div class="col-sm-offset-3 col-sm-6" style="margin-top: 20px;">
                <a class="btn btn-default btn-close" href="{{ route('task.index') }}">
                    <i class="fa fa-btn fa-ban"></i>Cancel</a>
            </div>
            @endif
        </div>
    </form>
</div>