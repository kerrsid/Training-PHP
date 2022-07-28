@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-sm-offset-2 col-sm-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                Task details
            </div>
            
            <div class="task-form-group">
                <a class="link" href={{ route('task.index') }}>
                    <i class="fa fa-btn fa-close" id="close-task-btn"></i>
                </a>
                <div class="form-horizontal panel-body form-group">
                    @include('partials/note-form', ['editable' => "readonly"])
                </div>
            </div>
        </div>    
    </div>
</div>
@endsection