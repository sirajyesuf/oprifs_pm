<x-guest-layout>
    <x-auth-card>
  
@section('page-title') {{__('Reset Password')}} @endsection
@section('content')

    <div class="login-form">
        <div class="page-title"><h5>{{ __('Reset Password') }}</h5></div>
        <form method="POST" action="{{ route('password.email') }}">
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

            <button type="submit" class="btn-login">{{ __('Reset Password') }}</button>
            <div class="or-text">{{ __('OR') }}</div>
            <a href="{{ route('login', $lang) }}" class="btn-login login-gray-btn">{{ __('Log In') }}</a>

        </form>
    </div>

@endsection

    </x-auth-card>
</x-guest-layout>
