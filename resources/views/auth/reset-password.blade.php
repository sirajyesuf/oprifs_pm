<x-guest-layout>
    <x-auth-card>


@section('page-title') {{__('Reset Password')}} @endsection

@section('content')

    <div class="login-form">
        <div class="page-title"><h5>{{ __('Reset Password') }}</h5></div>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
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
                <input class="form-control @error('password') is-invalid @enderror" name="password" type="password" required autocomplete="new-password" id="password" placeholder="{{ __('Enter Your Password') }}">
                @error('password')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-control-label">{{ __('Confirm Password') }}</label>
                <input class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" type="password" required autocomplete="new-password" id="password_confirmation" placeholder="{{ __('Enter Your Password') }}">
            </div>

            <button type="submit" class="btn-login">{{ __('Reset Password') }}</button>

        </form>
    </div>

@endsection
    </x-auth-card>
</x-guest-layout>

