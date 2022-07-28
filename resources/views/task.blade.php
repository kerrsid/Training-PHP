@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-sm-offset-2 col-sm-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                Task details
            </div>
            <a class="link" href={{ route('task.index') }}>
                    <i class="fa fa-btn fa-close" id="close-task-btn"></i>
            </a>
            <div class="task-form-group">
                @include('partials/note-form')
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("task-name").setAttribute("readonly", "true");
    document.getElementById("task-note").setAttribute("readonly", "true");
</script>
@endsection