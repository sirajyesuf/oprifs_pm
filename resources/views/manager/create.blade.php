<form method="POST" action="{{ route('manager.store') }}">
    @csrf
    <div class="form-group">
        <label for="fullname" class="form-control-label">{{ __('Full Name') }}</label>
        <input class="form-control" name="name" type="text" id="fullname" placeholder="{{ __('Enter Your Name') }}" value="{{ old('name') }}" required autocomplete="name">
    </div>
    
    <div class="form-group">
        <label for="emailaddress" class="form-control-label">{{ __('Email Address') }}</label>
        <input class="form-control" name="email" type="email" id="emailaddress" required autocomplete="email" placeholder="{{ __('Enter Your Email') }}" value="{{ old('email') }}">
    </div>
    <div class="form-group">
        <label for="password" class="form-control-label">{{ __('Password') }}</label>
        <input class="form-control" name="password" type="password" required autocomplete="new-password" id="password" placeholder="{{ __('Enter Your Password') }}">
    </div>
    <div class="col-md-12">
        <input type="submit" value="{{ __('Create') }}" class="btn-create badge-blue">
        <input type="button" value="{{ __('Cancel') }}" class="btn-create bg-gray" data-dismiss="modal">
    </div>
</form>
