<form class="px-3" method="post" action="{{ route('projects.update',[$currentWorkspace->slug,$project->id]) }}">
    @csrf
    <div class="row">
        <div class="form-group col-md-12">
            <label for="projectname" class="form-control-label">{{ __('Name') }}</label>
            <input class="form-control" type="text" id="projectname" name="name" required="" placeholder="{{ __('Project Name') }}" value="{{$project->name}}">
        </div>
        <div class="form-group col-md-12">
            <label for="description" class="form-control-label">{{ __('Description') }}</label>
            <textarea class="form-control" id="description" name="description" required="" placeholder="{{ __('Add Description') }}">{{$project->description}}</textarea>
        </div>
        <div class="form-group col-md-6">
            <label for="status" class="form-control-label">{{ __('Status') }}</label>
            <select id="status" name="status" class="form-control select2">
                <option value="Ongoing">{{ __('Ongoing') }}</option>
                <option value="Finished" @if($project->status == 'Finished') selected @endif>{{ __('Finished') }}</option>
                <option value="OnHold" @if($project->status == 'OnHold') selected @endif>{{ __('OnHold') }}</option>
            </select>
        </div>

        {{-- <div class="form-group col-md-6">
            <label for="budget" class="form-control-label">{{ __('Budget') }}</label>
            <div class="form-icon-user">
                <span class="currency-icon">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                <input class="form-control" type="number" min="0" id="budget" name="budget" value="{{$project->budget}}" placeholder="{{ __('Project Budget') }}">
            </div>
        </div> --}}
        <div class="form-group col-md-6">
            <label for="start_date" class="form-control-label">{{ __('Start Date') }}</label>
            <input class="form-control datepicker" type="text" id="start_date" name="start_date" value="{{$project->start_date}}" autocomplete="off" required="required">
        </div>
        <div class="form-group col-md-6">
            <label for="end_date" class="form-control-label">{{ __('End Date') }}</label>
            <input class="form-control datepicker" type="text" id="end_date" name="end_date" value="{{$project->end_date}}" autocomplete="off" required="required">
        </div>
        <div class="col-md-12">
            <input type="submit" class="btn-create badge-blue" value="{{ __('Save') }}">
            <input type="button" class="btn-create bg-gray" data-dismiss="modal" value="{{ __('Cancel') }}">
        </div>
    </div>
</form>
