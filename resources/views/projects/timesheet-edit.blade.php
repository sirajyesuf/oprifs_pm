{{ Form::model($timesheet, ['route' => ['project.timesheet.update',['slug' => $currentWorkspace->slug, 'timesheet_id' => $timesheet->id, 'project_id' => $project_id]], 'method' => 'POST']) }}

<input type="hidden" name="project_id" value="{{ $parseArray['project_id'] }}">
<input type="hidden" name="task_id" value="{{ $parseArray['task_id'] }}">
<input type="hidden" name="date" value="{{ $timesheet->date }}">
<input type="hidden" id="totaltasktime" value="{{ $parseArray['totaltaskhour'] . ':' . $parseArray['totaltaskminute'] }}">

<div class="form-group">
    <label class="form-control-label">{{ __('Project')}}</label>
    <input type="text" class="form-control" value="{{ $parseArray['project_name'] }}" disabled="disabled">
</div>

<div class="form-group">
    <label class="form-control-label">{{ __('Task')}}</label>
    <input type="text" class="form-control" value="{{ $parseArray['task_name'] }}" disabled="disabled">
</div>

<div class="row">
    <div class="col-md-12">
        <label for="time" class="form-control-label">{{ __('Time')}}</label>
    </div>
    <div class="col-md-6">
        <select class="form-control select2" name="time_hour" id="time_hour" required="">
            <option value="">{{ __('Hours') }}</option>

            <?php for ($i = 0; $i < 23; $i++) { $i = $i < 10 ? '0' . $i : $i; ?>

            <option value="{{ $i }}" {{ $parseArray['time_hour'] == $i ? 'selected="selected"' : '' }}>{{ $i }}</option>

            <?php } ?>

        </select>
    </div>

    <div class="col-md-6">
        <select class="form-control select2" name="time_minute" id="time_minute" required>
            <option value="">{{ __('Minutes')}}</option>

            <?php for ($i = 0; $i < 61; $i += 10) { $i = $i < 10 ? '0' . $i : $i; ?>

            <option value="{{ $i }}" {{ $parseArray['time_minute'] == $i ? 'selected="selected"' : '' }}>{{ $i }}</option>

            <?php } ?>

        </select>
    </div>

    <div class="col-md-12">
        <label for="description" class="form-control-label">{{ __('Description')}}</label>
        <textarea class="form-control " id="description" rows="3" name="description">{{ $timesheet->description }}</textarea>
    </div>
    <div class="col-md-12">
        <div class="display-total-time">
            <i class="fas fa-clock"></i>
            <span>{{ __('Total Time') }} : {{ $parseArray['totaltaskhour'] . ' ' . __('Hours') . ' ' . $parseArray['totaltaskminute'] . ' ' . __('Minutes') }}</span>
        </div>
    </div>

    @php($id = str_replace('.', '', uniqid('', true)))

    <div class="col-md-12">
        <input type="submit" class="btn-create badge-blue" value="{{ __('Save') }}">
        <input type="button" class="btn-create bg-gray" data-dismiss="modal" value="{{ __('Cancel') }}">

        <a href="#" onclick="(confirm('{{__('Are you sure ?')}}')?document.getElementById('delete-form-{{$id}}').submit(): '');" class="float-right btn-create bg-red">
            {{ __('Delete')}}
        </a>
    </div>
</div>

{{ Form::close() }}

<form id="delete-form-{{$id}}" action="{{ route('timesheet.destroy',[$currentWorkspace->slug,$timesheet->id]) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
