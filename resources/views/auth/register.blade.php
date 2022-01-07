<x-guest-layout>
    <x-auth-card>
    

@section('page-title') {{__('Register')}} @endsection
@section('language-bar')
        <div class="all-select">
        <a href="#" class="monthly-btn ">
            <span class="monthly-text width-button">{{__('Change Language')}}</span>
            <select name="language" id="language" class=" populate  select-box" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                @foreach(App\Models\Utility::languages() as $language)
                    <option @if($lang == $language) selected @endif value="{{ route('register',$language) }}">{{Str::upper($language)}}</option>
                @endforeach
            </select>
        </a>
    </div>         
@endsection
@section('content')

    <div class="login-form">
        <div class="page-title"><h5>{{ __('Sign Up') }}</h5></div>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="fullname" class="form-control-label">{{ __('Full Name') }}</label>
                <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" id="fullname" placeholder="{{ __('Enter Your Name') }}" value="{{ old('name') }}" required autocomplete="name">
                @error('name')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="workspace_name" class="form-control-label">{{ __('Workspace Name') }}</label>
                <input class="form-control @error('workspace_name') is-invalid @enderror" name="workspace" type="text" id="workspace_name" placeholder="{{ __('Enter Workspace Name') }}" value="{{ old('workspace') }}" required autocomplete="workspace">
                @error('company')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="emailaddress" class="form-control-label">{{ __('Email Address') }}</label>
                <input class="form-control @error('email') is-invalid @enderror" name="email" type="email" id="emailaddress" required autocomplete="email" placeholder="{{ __('Enter Your Email') }}" value="{{ old('email') }}">
                @error('email')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password" class="form-control-label">{{ __('Password') }}</label>
                <input class="form-control @error('password') is-invalid @enderror" name="password" type="password" required autocomplete="new-password" id="password" placeholder="{{ __('Enter Your Password') }}">
                @error('password')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password_confirmation" class="form-control-label">{{ __('Confirm Password') }}</label>
                <input class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" type="password" required autocomplete="new-password" id="password_confirmation" placeholder="{{ __('Confirm Your Password') }}">
            </div>
            <div class="custom-control custom-checkbox remember-me-text">
                <input type="checkbox" class="custom-control-input" id="checkbox-signin" name="accept" required {{ old('remember') ? 'checked' : '' }}>
                <label class="custom-control-label" for="checkbox-signin">{{ __('Accept terms and privacy') }}</label>
            </div>

            <button type="submit" class="btn-login">{{ __('Register') }}</button>
            <div class="or-text">{{ __('OR') }}</div>
            <a href="{{ route('login', $lang) }}" class="btn-login login-gray-btn">{{ __('Sign In') }}</a>
        </form>
    </div>

@endsection

    </x-auth-card>
</x-guest-layout>
