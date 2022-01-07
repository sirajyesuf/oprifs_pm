<x-guest-layout>
    <x-auth-card>

@section('page-title') {{__('Client Login')}} @endsection

@section('content')

    <div class="login-form">
        <ul class="login-menu">
            <li class="gray-login"><a href="{{route('login', $lang)}}">{{ __('User Login') }}</a></li>
            <li class="blue-login"><a href="#">{{ __('Client Login') }}</a></li>
        </ul>
        <div class="page-title"><h5><span>{{ __('Client') }}</span> {{ __('Login') }}</h5></div>
        <form method="POST" action="{{ route('client.login') }}">
            @csrf
            <div class="form-group">
                <label for="email" class="form-control-label">{{ __('Email') }}</label>
                <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" id="emailaddress" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('Enter Your Email') }}">
                @error('email')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password" class="form-control-label">{{ __('Password') }}</label>
                <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password" id="password" placeholder="{{ __('Enter Your Password') }}">
                @error('password')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="custom-control custom-checkbox remember-me-text">
                <input type="checkbox" class="custom-control-input" id="remember-me" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="custom-control-label" for="remember-me">{{ __('Remember Me') }}</label>
            </div>

            <button type="submit" class="btn-login">{{ __('Login') }}</button>
            <a href="{{ route('password.request', $lang) }}" class="text-muted"><small>{{ __('Forgot your password?') }}</small></a>
            <div class="or-text">{{ __('OR') }}</div>
            <a href="{{ route('register', $lang) }}" class="btn-login login-gray-btn">{{ __('Sign Up') }}</a>
        </form>
    </div>

@endsection
    </x-auth-card>
</x-guest-layout>