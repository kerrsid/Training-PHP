@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-sm-offset-2 col-sm-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                Task details
            </div>
            @include('common.errors')
            <div class="task-form-group">
                <a class="link" href={{ route('task.index') }}>
                    <i class="fa fa-btn fa-close" id="close-task-btn"></i>
                </a>
                <div class="form-horizontal panel-body form-group">
                    @include('partials/note-form', ['readonly' => true])
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-offset-2 col-sm-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                Manage file:
            </div>
            <div class="task-form-group">

                @if($task->file_path == "")

                <div class="form-horizontal panel-body form-group">
                    <form action="{{ route('file.upload', $task->id) }}" method="POST" enctype="multipart/form-data" id="upload-form">
                        {{ csrf_field() }}
                        {{ method_field('POST')}}

                        <div class="col-sm-6">
                            <input class="form-control" id="file-upload-form-field" type="file" name="file" />
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-primary" type="button" onclick="uploadDocument()">Upload file</button>
                        </div>
                    </form>
                </div>

                @else

                <div class="col-md-12">
                    <form action="{{ route('file.delete', $task->id) }}" method="POST" id="form-delete-doc">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                    </form>       
                   
                   <p>Rename: </p>
                   <form action="{{ route('file.rename', $task->id) }}" method="POST" id="form-rename-doc">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <input type="text" name="fileName" id="file-name" class="form-control" 
                               value="{{ substr(basename($task->file_path), 0, strrpos(basename($task->file_path), '.')) }} ">
                    </form>

                    <button type="button" class="btn btn-success" onclick="renameDocument()">
                        <i class="fa fa-btn fa-edit"></i>&ensp;Rename
                    </button>

                    <a href="{{ route('file.download', $task->id) }}">
                        <button type="button" class="btn btn-primary">
                            <i class="fa fa-btn fa-download"></i>&ensp;Download
                        </button>
                    </a>

                    <button type="button" class="btn btn-primary" onclick="previewDocument()">
                        <i class="fa fa-btn fa-edit"></i>&ensp;Preview
                    </button>

                    <button type="button" class="btn btn-primary" id="print-btn" onclick="printDocument()">
                        <i class="fa fa-btn fa-print"></i>&ensp;Print
                    </button>

                    <button type="button" class="btn btn-danger" onclick="deleteDocument()">
                        <i class="fa fa-btn fa-trash"></i>&ensp;Delete
                    </button>
                </div>

                <iframe data-src="{{ route('file.preview', $task->id) }}" src="about:blank" class="col-md-10" id="document-view"></iframe>

                @endif
            </div>
        </div>
    </div>
</div>

<script>

    function uploadDocument() {
        var doc = document.getElementById("file-upload-form-field");
         
       if (typeof (doc.files[0]) != 'undefined') {
            var size = parseFloat(doc.files[0].size / 1024).toFixed(2);
            
            if (size > 2048){
                alert("This document is too big to be loaded on this server! (max-size: 2MB)");
                return;
            }
        }

        document.getElementById("upload-form").submit();
    }

    function deleteDocument() {
        document.getElementById("form-delete-doc").submit();
    }

    function previewDocument() {
        var iframe = $("#document-view");

        if (iframe.attr("src") === iframe.data("src")) {
            iframe.attr("src", "about:blank");
        } else {
            iframe.attr("src", iframe.data("src"));
        }

        return;
    }

    function printDocument() {
        if ($("#document-view").contents().find("body").is(':empty')) {
            alert("Nothing to print! (Please open document preview)!!");
            return;
        }

        document.getElementById("document-view").contentWindow.print();
    }

     function renameDocument() {
        document.getElementById("form-rename-doc").submit();
    }
</script>

@endsection

