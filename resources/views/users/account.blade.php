@extends('layouts.admin')

@section('page-title') {{__('User Profile')}} @endsection

@section('content')
    <section class="section">

        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body p-4">
                        <ul class="nav nav-tabs my-4">
                            <li>
                                <a data-toggle="tab" href="#v-pills-home" class="active">{{__('Account')}}</a>
                            </li>
                            <li class="annual-billing">
                                <a data-toggle="tab" href="#v-pills-profile" class="">{{__('Change Password')}} </a>
                            </li>
                            @auth('client')
                                <li class="annual-billing">
                                    <a data-toggle="tab" href="#v-pills-billing" class="">{{__('Billing Details')}} </a>
                                </li>
                            @endauth
                        </ul>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="tab-content animated" id="v-pills-tabContent">
                                    <div class="tab-pane fade active show" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                        <form method="post" action="@auth('web'){{route('update.account')}}@elseauth{{route('client.update.account')}}@endauth" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <h6 class="small-title">
                                                            {{__('Avatar')}}
                                                        </h6>
                                                        <img @if($user->avatar) src="{{asset('/storage/avatars/'.$user->avatar)}}" @else avatar="{{ $user->name }}" @endif id="myAvatar" alt="user-image" class="rounded-circle img-thumbnail w-25">
                                                        @if($user->avatar!='')
                                                            <a href="#" class="delete-icon" data-confirm="{{ __('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="document.getElementById('delete_avatar').submit();"><i class="fas fa-trash"></i></a>
                                                        @endif
                                                        <div class="choose-file mt-3">
                                                            <label for="avatar">
                                                                <div>{{__('Choose file here')}}</div>
                                                                <input type="file" class="form-control" name="avatar" id="avatar" data-filename="avatar-logo">
                                                            </label>
                                                            <p class="avatar-logo"></p>
                                                            @error('avatar')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <small class="d-inline-block mt-2">{{ __('Please upload a valid image file. Size of image should not be more than 2MB.') }}</small>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="name" class="form-control-label">{{ __('Full Name') }}</label>
                                                        <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" id="fullname" placeholder="{{ __('Enter Your Name') }}" value="{{ $user->name }}" required autocomplete="name">
                                                        @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email" class="form-control-label">{{ __('Email') }}</label>
                                                        <input class="form-control @error('email') is-invalid @enderror" name="email" type="text" id="email" placeholder="{{ __('Enter Your Email Address') }}" value="{{ $user->email }}" required autocomplete="email">
                                                        @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-sm-6">
                                                    <div class="">
                                                        <button type="submit" class="btn-submit">
                                                            {{ __('Update')}}
                                                        </button>
                                                    </div>
                                                </div> <!-- end col -->
                                            </div> <!-- end row -->
                                        </form>
                                        @if($user->avatar!='')
                                            <form action="@auth('web'){{route('delete.avatar')}}@elseauth{{route('client.delete.avatar')}}@endauth" method="post" id="delete_avatar">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                        @auth('web')
                                            <a href="#" class="btn btn-xs btn-danger mt-5" data-confirm="{{ __('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="document.getElementById('delete-my-account').submit();">
                                                {{ __('Delete')}} {{__('My Account')}}
                                            </a>
                                            <form action="{{route('delete.my.account')}}" method="post" id="delete-my-account">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endauth
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                        <form method="post" action="@auth('web'){{route('update.password')}}@elseauth{{route('client.update.password')}}@endauth">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="old_password" class="form-control-label">{{ __('Old Password') }}</label>
                                                        <input class="form-control @error('old_password') is-invalid @enderror" name="old_password" type="password" id="old_password" required autocomplete="old_password" placeholder="{{ __('Enter Old Password') }}">
                                                        @error('old_password')
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
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-sm-6">
                                                    <button type="submit" class="btn-submit"> {{ __('Change Password') }} </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    @auth('client')
                                        <div class="tab-pane fade" id="v-pills-billing" role="tabpanel" aria-labelledby="v-pills-billing-tab">
                                            <form method="post" action="{{route('client.update.billing')}}">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="address" class="form-control-label">{{__('Address')}}</label>
                                                        <input class="form-control font-style" name="address" type="text" value="{{ $user->address }}" id="address">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="city" class="form-control-label">{{__('City')}}</label>
                                                        <input class="form-control font-style" name="city" type="text" value="{{ $user->city }}" id="city">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="state" class="form-control-label">{{__('State')}}</label>
                                                        <input class="form-control font-style" name="state" type="text" value="{{ $user->state }}" id="state">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="zipcode" class="form-control-label">{{__('Zip/Post Code')}}</label>
                                                        <input class="form-control" name="zipcode" type="text" value="{{ $user->zipcode }}" id="zipcode">
                                                    </div>
                                                    <div class="form-group  col-md-6">
                                                        <label for="country" class="form-control-label">{{__('Country')}}</label>
                                                        <input class="form-control font-style" name="country" type="text" value="{{ $user->country }}" id="country">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="telephone" class="form-control-label">{{__('Telephone')}}</label>
                                                        <input class="form-control" name="telephone" type="text" value="{{ $user->telephone }}" id="telephone">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <button type="submit" class="btn-submit">
                                                            {{ __('Update')}}
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    @endauth

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
