<div class="form-group">
    <div class=".container-sm ">
        <label for="task-name" class="col-sm-3 control-label">Task</label>
        <div class="col-sm-6">
            <input type="text" name="name" id="task-name" class="form-control" value="{{ $task->name or old('add') }}" {{ isset($readonly ) && $readonly === true ? 'readonly' : ''}}>
        </div>
    </div>
    <br>
    <br>
    <br>
    <div class=".container-sm" style="margin-bottom: 5px;">
        <label for="task-note" class="col-sm-3 control-label">Note</label>
        <div class="col-sm-6">
            <textarea class="form-control" 
                      name="note" 
                      id="task-note" 
                      rows="3" 
                      {{isset($readonly ) && $readonly === true ? 'readoly' : ''}}>{{ $task->note or old('add') }}</textarea>
        </div>
    </div>
</div>