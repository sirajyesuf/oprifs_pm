@extends('layouts.admin')

@section('page-title') {{__('Clients')}} @endsection

@section('action-button')
    @auth('web')
        @if(isset($currentWorkspace) && $currentWorkspace->creater->id == Auth::id())
            <a href="#" class="btn btn-xs btn-white btn-icon-only width-auto" data-ajax-popup="true" data-size="lg" data-title="{{ __('Add Client') }}" data-url="{{route('clients.create',$currentWorkspace->slug)}}">
                <i class="fa fa-plus"></i> {{ __('Add Client') }}
            </a>
        @endif
    @endauth
@endsection

@section('content')
    <section class="section">

        @if($currentWorkspace)

            <div class="row">
                @foreach ($clients as $client)
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="card profile-card">
                            <div class="edit-profile user-text">
                                @if($client->is_active)
                                    <a href="#" class="edit-icon" data-ajax-popup="true" data-size="lg" data-title="{{__('Edit Client')}}" data-url="{{route('clients.edit',[$currentWorkspace->slug,$client->id])}}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <a href="#" class="delete-icon" data-confirm="{{ __('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="document.getElementById('delete-form-{{ $client->id }}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['clients.destroy',[$currentWorkspace->slug,$client->id]],'id'=>'delete-form-'.$client->id]) !!}
                                    {!! Form::close() !!}
                                @else
                                    <a href="#" class="lock-icon" title="{{__('Locked')}}">
                                        <i class="fas fa-lock"></i>
                                    </a>
                                @endif
                            </div>
                            <img avatar="{{ $client->name }}" alt="" class="rounded-circle img-thumbnail">
                            <h4 class="h4 mb-0 mt-2">{{ $client->name }}</h4>
                            <h6 class="office-time mb-0 mt-4">{{ $client->email }}</h6>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="container mt-5">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="page-error">
                            <div class="page-inner">
                                <h1>404</h1>
                                <div class="page-description">
                                    {{ __('Page Not Found') }}
                                </div>
                                <div class="page-search">
                                    <p class="text-muted mt-3">{{ __("It's looking like you may have taken a wrong turn. Don't worry... it happens to the best of us. Here's a little tip that might help you get back on track.")}}</p>
                                    <div class="mt-3">
                                        <a class="btn-return-home badge-blue" href="{{route('home')}}"><i class="fas fa-reply"></i> {{ __('Return Home')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>
    <!-- container -->
@endsection
