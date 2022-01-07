<form class="px-3" method="post" action="{{ route('notes.update',[$currentWorkspace->slug,$note->id]) }}">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <label for="title" class="form-control-label">{{ __('Title') }}</label>
            <input class="form-control" type="text" id="title" name="title" placeholder="{{ __('Enter Title') }}" value="{{$note->title}}" required>
        </div>
        <div class="col-md-12">
            <label for="description" class="form-control-label">{{ __('Description') }}</label>
            <textarea class="form-control" id="description" name="text" placeholder="{{ __('Enter Description') }}" required>{{$note->text}}</textarea>
        </div>
        <div class="col-md-12">
            <label for="color" class="form-control-label">{{__('Color')}}</label>
            <select class="form-control select2" name="color" id="color" required>
                <option value="bg-primary">{{ __('Primary')}}</option>
                <option @if($note->color == 'bg-secondary') selected @endif value="bg-secondary">{{ __('Secondary')}}</option>
                <option @if($note->color == 'bg-info') selected @endif value="bg-info">{{ __('Info')}}</option>
                <option @if($note->color == 'bg-warning') selected @endif value="bg-warning">{{ __('Warning')}}</option>
                <option @if($note->color == 'bg-danger') selected @endif value="bg-danger">{{ __('Danger')}}</option>
            </select>
        </div>
        <div class="col-md-12">
            <label for="type" class="form-control-label">{{__('Type')}}</label>
            <div class="selectgroup w-50">
                <label class="selectgroup-item">
                    <input type="radio" name="type" value="personal" id="personal" class="selectgroup-input" {{ $note->type == 'personal' ? 'checked="checked"' : '' }}>
                    <span class="selectgroup-button">{{ __('Personal') }}</span>
                </label>
                <label class="selectgroup-item">
                    <input type="radio" name="type" value="shared" id="shared" class="selectgroup-input" {{ $note->type == 'shared' ? 'checked="checked"' : '' }}>
                    <span class="selectgroup-button">{{ __('Shared') }}</span>
                </label>
            </div>
        </div>
        <div class="col-md-12 assign_to_selection">
            <label for="assign_to" class="form-control-label">{{__('Assign to')}}</label>
            <select class="form-control form-control-light select2" multiple="multiple" id="assign_to" name="assign_to[]">
                @foreach($users as $u)
                    <option value="{{$u->id}}" @if(in_array($u->id, $note->assign_to)) selected @endif>{{$u->name}} - {{$u->email}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12">
            <input type="submit" class="btn-create badge-blue" value="{{ __('Save') }}">
            <input type="button" class="btn-create bg-gray" data-dismiss="modal" value="{{ __('Cancel') }}">
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#{{ $note->type }}').trigger('click');
    });
</script>
