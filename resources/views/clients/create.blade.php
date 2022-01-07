<form class="px-3" method="post" action="{{ route('clients.store',$currentWorkspace->slug) }}">
    @csrf
    <div class="row">
        <div class="form-group col-md-12">
            <label for="name" class="form-control-label">{{ __('Name') }}</label>
            <input class="form-control" type="text" id="name" name="name" required="" placeholder="{{ __('Enter Name') }}">
        </div>
        <div class="form-group col-md-12">
            <label for="email" class="form-control-label">{{ __('Email') }}</label>
            <input class="form-control" type="email" id="email" name="email" required="" placeholder="{{ __('Enter Email') }}">
        </div>
        <div class="form-group col-md-12">
            <label for="password" class="form-control-label">{{ __('Password') }}</label>
            <input class="form-control" type="text" id="password" name="password" required="" placeholder="{{ __('Enter Password') }}">
        </div>
        <div class="col-md-12">
            <input type="submit" value="{{ __('Create') }}" class="btn-create badge-blue">
            <input type="button" value="{{ __('Cancel') }}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
</form>
