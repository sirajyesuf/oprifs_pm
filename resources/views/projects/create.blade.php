<form class="px-3" method="post" action="{{ route('projects.store',$currentWorkspace->slug) }}">
    @csrf
    <div class="row">
        <div class="form-group col-md-12">
            <label for="projectname" class="form-control-label">{{ __('Name') }}</label>
            <input class="form-control" type="text" id="projectname" name="name" required="" placeholder="{{ __('Project Name') }}">
        </div>
        <div class="form-group col-md-12">
            <label for="description" class="form-control-label">{{ __('Description') }}</label>
            <textarea class="form-control" id="description" name="description" required="" placeholder="{{ __('Add Description') }}"></textarea>
        </div>
        <div class="col-md-12">
            <label for="users_list">{{ __('Users') }}</label>
            <select class="select2 select2-multiple" id="users_list" name="users_list[]" data-toggle="select2" multiple="multiple" data-placeholder="{{ __('Select Users ...') }}">
                @foreach($currentWorkspace->users($currentWorkspace->created_by) as $user)
                    <option value="{{$user->email}}">{{$user->name}} - {{$user->email}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12">
            <input type="submit" value="{{ __('Create') }}" class="btn-create badge-blue">
            <input type="button" value="{{ __('Cancel') }}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
</form>
