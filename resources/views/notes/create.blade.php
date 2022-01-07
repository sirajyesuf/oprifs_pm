<form class="px-3" method="post" action="{{ route('notes.store',$currentWorkspace->slug) }}">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <label for="title" class="form-control-label">{{ __('Title') }}</label>
            <input class="form-control" type="text" id="title" name="title" placeholder="{{ __('Enter Title') }}" required>
        </div>
        <div class="col-md-12">
            <label for="description" class="form-control-label">{{ __('Description') }}</label>
            <textarea class="form-control" id="description" name="text" placeholder="{{ __('Enter Description') }}" required></textarea>
        </div>
        <div class="col-md-12">
            <label for="color" class="form-control-label">{{__('Color')}}</label>
            <select class="form-control select2" name="color" id="color" required>
                <option value="bg-primary">{{ __('Primary')}}</option>
                <option value="bg-secondary">{{ __('Secondary')}}</option>
                <option value="bg-info">{{ __('Info')}}</option>
                <option value="bg-warning">{{ __('Warning')}}</option>
                <option value="bg-danger">{{ __('Danger')}}</option>
            </select>
        </div>
        <div class="col-md-12">
            <label for="type" class="form-control-label">{{__('Type')}}</label>
            <div class="selectgroup w-50">
                <label class="selectgroup-item">
                    <input type="radio" name="type" value="personal" class="selectgroup-input" checked="checked">
                    <span class="selectgroup-button">{{ __('Personal') }}</span>
                </label>
                <label class="selectgroup-item">
                    <input type="radio" name="type" value="shared" class="selectgroup-input">
                    <span class="selectgroup-button">{{ __('Shared') }}</span>
                </label>
            </div>
        </div>
        <div class="col-md-12 assign_to_selection">
            <label for="assign_to" class="form-control-label">{{__('Assign to')}}</label>
            <select class="form-control form-control-light select2" multiple="multiple" id="assign_to" name="assign_to[]">
                @foreach($users as $u)
                    <option value="{{$u->id}}">{{$u->name}} - {{$u->email}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12">
            <input type="submit" class="btn-create badge-blue" value="{{ __('Create') }}">
            <input type="button" class="btn-create bg-gray" data-dismiss="modal" value="{{ __('Cancel') }}">
        </div>
    </div>
</form>
