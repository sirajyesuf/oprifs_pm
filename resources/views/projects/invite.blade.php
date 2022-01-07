<form class="px-3" method="post" action="{{ route('projects.invite.update',[$currentWorkspace->slug,$project->id]) }}">
    @csrf
    <div class="form-group col-md-12 mb-0">
        <label for="users_list">{{ __('Users') }}</label>
        <select class="select2 form-control select2-multiple" required id="users_list" name="users_list[]" data-toggle="select2" multiple="multiple" data-placeholder="{{ __('Select Users ...') }}">
            @foreach($currentWorkspace->users($currentWorkspace->created_by) as $user)
                @if($user->pivot->is_active)
                    <option value="{{$user->email}}">{{$user->name}} - {{$user->email}}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="col-md-12">
        <input type="submit" value="{{ __('Invite Users') }}" class="btn-create badge-blue">
        <input type="button" value="{{ __('Cancel') }}" class="btn-create bg-gray" data-dismiss="modal">
    </div>
</form>
