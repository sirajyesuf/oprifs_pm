<form class="px-3" method="post" action="{{ route('users.update',[$currentWorkspace->slug,$user->id]) }}">
    @csrf
     @method('post')
    <div class="row">
        <div class="col-md-12">
            <label for="name" class="form-control-label">{{ __('Name')}}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{$user->name}}"/>
        </div>
        <div class="col-md-12">
            <input type="submit" value="{{ __('Save') }}" class="btn-create badge-blue">
            <input type="button" value="{{ __('Cancel') }}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
</form>
