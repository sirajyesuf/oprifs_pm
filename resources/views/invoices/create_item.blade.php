<form method="post" action="{{ route('invoice.item.store',[$currentWorkspace->slug,$invoice->id]) }}">
    @csrf
    <div class="col-md-12">
        <label for="task" class="form-control-label">{{__('Tasks')}}</label>
        <select class="form-control select2" name="task" id="task" required>
            <option value="">{{__('Select Task')}}</option>
            @foreach($invoice->project->tasks() as $task)
                <option value="{{$task->id}}">{{$task->title}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-12">
        <label for="price" class="form-control-label">{{__('Price')}}</label>
        <div class="form-icon-user">
            <span class="currency-icon">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$' }}</span>
            <input class="form-control" type="number" min="0" value="0" id="price" name="price" required>
        </div>
    </div>
    <div class="col-md-12">
        <input type="submit" class="btn-create badge-blue" value="{{ __('Add') }}">
        <input type="button" class="btn-create bg-gray" data-dismiss="modal" value="{{ __('Cancel') }}">
    </div>
</form>
