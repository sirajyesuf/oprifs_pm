<form class="px-3" method="post" action="{{ route('clients.update',[$currentWorkspace->slug,$client->id]) }}">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <label for="name" class="form-control-label">{{ __('Name') }}</label>
            <input class="form-control" type="text" id="name" name="name" required="" placeholder="{{ __('Enter Name') }}" value="{{$client->name}}">
        </div>
        <div class="col-md-12">
            <label for="password" class="form-control-label">{{ __('Password') }}</label>
            <input class="form-control" type="text" id="password" name="password" required="" placeholder="{{ __('Enter Password') }}">
        </div>
        <div class="col-md-12">
            <input type="submit" value="{{ __('Save') }}" class="btn-create badge-blue">
            <input type="button" value="{{ __('Cancel') }}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
</form>
