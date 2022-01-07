@extends('layouts.admin')

@section('page-title') {{ __('Dashboard') }} @endsection

@section('multiple-action-button')
    @auth('web')
        @if (Auth::user()->type == 'admin')
            <a href="#" class="btn btn-xs btn-white btn-icon-only width-auto" data-ajax-popup="true" data-size="md"
                data-title="{{ __('Add User') }}" data-url="{{ route('users.create') }}">
                <i class="fa fa-plus"></i> {{ __('Add User') }}
            </a>
            <a href="#" class="btn btn-xs btn-white btn-icon-only width-auto" data-ajax-popup="true" data-size="md"
                data-title="{{ __('Add Manager') }}" data-url="{{ route('manager.create') }}">
                <i class="fa fa-plus"></i> {{ __('Add Manager') }}
            </a>
        @elseif(isset($currentWorkspace) && $currentWorkspace->creater->id == Auth::id())
            <a href="#" class="btn btn-xs btn-white btn-icon-only width-auto" data-ajax-popup="true" data-size="md"
                data-title="{{ __('Invite New User') }}" data-url="{{ route('users.invite', $currentWorkspace->slug) }}">
                <i class="fa fa-plus"></i> {{ __('Invite User') }}
            </a>
        @endif
    @endauth
@endsection

@section('content')
    <!-- Start Content-->
    <section class="section">
        @if (Auth::user()->type == 'admin')
            <div class="row mt-3">
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <div class="card card-box">
                        <div class="left-card">
                            <div class="icon-box bg-primary"><i class="fas fa-tasks"></i></div>
                            <h4>{{ __('Total') }} <span>{{ __('Projects') }}</span></h4>
                        </div>
                        <div class="number-icon">{{ $totalProject }}</div>
                    </div>
                    <img src="{{ asset('assets/img/dot-icon.png') }}" class="dotted-icon">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <div class="card card-box">
                        <div class="left-card">
                            <div class="icon-box bg-info"><i class="fas fa-tag"></i></div>
                            <h4>{{ __('Total') }} <span>{{ __('Tasks') }}</span></h4>
                        </div>
                        <div class="number-icon">{{ $totalTask }}</div>
                    </div>
                    <img src="{{ asset('assets/img/dot-icon.png') }}" class="dotted-icon">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <div class="card card-box">
                        @if (Auth::user()->type == 'admin')
                            <div class="left-card">
                                <div class="icon-box bg-success"><i class="fas fa-users"></i></div>
                                <h4>{{ __('Total') }} <span>{{ __('Users') }}</span></h4>
                            </div>
                            <div class="number-icon">{{ $totalUsers }}</div>

                        @else
                        <div class="left-card">
                            <div class="icon-box bg-success"><i class="fas fa-users"></i></div>
                            <h4>{{ __('Total') }} <span>{{ __('Members') }}</span></h4>
                        </div>
                        <div class="number-icon">{{ $totalMembers }}</div>

                        @endif
                        
                    </div>
                    <img src="{{ asset('assets/img/dot-icon.png') }}" class="dotted-icon">
                </div>
                {{-- <div class="col-lg-3 col-md-3 col-sm-6">
                    <div class="card card-box">
                        <div class="left-card">
                            <div class="icon-box bg-danger"><i class="fas fa-bug"></i></div>
                            <h4>{{ __('Total') }} <span>{{ __('Bugs') }}</span></h4>
                        </div>
                        <div class="number-icon">{{ $totalBugs }}</div>
                    </div>
                    <img src="{{ asset('assets/img/dot-icon.png') }}" class="dotted-icon">
                </div> --}}
            </div>


        @elseif(isset($currentWorkspace) && $currentWorkspace )
            <div class="row">
                @foreach ($users as $user)
                    @php($workspace_id = isset($currentWorkspace) && $currentWorkspace ? $currentWorkspace->id : '')
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="card">
                            <div class="card-header border-0 pb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        @if (Auth::user()->type != 'admin')
                                            @if ($user->permission == 'Owner')
                                                <div class="badge badge-pill badge-success">{{ __('Owner') }}</div>
                                            @else
                                                <div class="badge badge-pill badge-warning">{{ __('Member') }}</div>
                                            @endif
                                        @endif
                                    </h6>
                                </div>
                            </div>
                            @if (isset($currentWorkspace) && $currentWorkspace && $currentWorkspace->permission == 'Owner' && Auth::user()->id != $user->id)
                                <div class="dropdown action-item edit-profile user-text">
                                    <a href="#" class="action-item p-2" role="button" data-toggle="dropdown"
                                        aria-expanded="false"><i class="fas fa-ellipsis-h"></i></a>
                                    <div class="dropdown-menu dropdown-menu-left">
                                        @if (isset($currentWorkspace) && $currentWorkspace && $currentWorkspace->permission == 'Owner' && Auth::user()->id != $user->id)
                                            <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="md"
                                                data-title="{{ __('Edit User') }}"
                                                data-url="{{ route('users.edit', [$currentWorkspace->slug, $user->id]) }}">{{ __('Edit') }}</a>
                                            <a href="#" class="dropdown-item text-danger"
                                                data-confirm="{{ __('Are You Sure?') . '|' . __('This action can not be undone. Do you want to continue?') }}"
                                                data-confirm-yes="document.getElementById('remove_user_{{ $user->id }}').submit();">{{ __('Remove User From Workspace') }}</a>
                                            <form
                                                action="{{ route('users.remove', [$currentWorkspace->slug, $user->id]) }}"
                                                method="post" id="remove_user_{{ $user->id }}" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="card-body text-center pb-3">
                                <a href="#" class="avatar rounded-circle avatar-lg hover-translate-y-n3">
                                    <img @if ($user->avatar) src="{{ asset('/storage/avatars/' . $user->avatar) }}" @else avatar="{{ $user->name }}" @endif>
                                </a>
                                <h5 class="h6 mt-4 mb-0">
                                    <a href="#" class="text-title">{{ $user->name }}</a>
                                </h5>
                                <span>{{ $user->email }}</span>
                                <hr class="my-3">
                                <div class="row align-items-center">
                                    @if (Auth::user()->type == 'admin')
                                        <div class="col-6">
                                            <div class="h6 mb-0">{{ $user->countWorkspace() }}</div>
                                            <span class="text-sm text-muted">{{ __('Workspaces') }}</span>
                                        </div>
                                        <div class="col-6">
                                            <div class="h6 mb-0">{{ $user->countUsers($workspace_id) }}</div>
                                            <span class="text-sm text-muted">{{ __('Users') }}</span>
                                        </div>
                                        <div class="col-6">
                                            <div class="h6 mb-0">{{ $user->countClients($workspace_id) }}</div>
                                            <span class="text-sm text-muted">{{ __('Clients') }}</span>
                                        </div>
                                    @endif
                                    <div class="col-6">
                                        <div class="h6 mb-0">{{ $user->countProject($workspace_id) }}</div>
                                        <span class="text-sm text-muted">{{ __('Projects') }}</span>
                                    </div>
                                    @if (Auth::user()->type != 'admin')
                                        <div class="col-6">
                                            <div class="h6 mb-0">{{ $user->countTask($workspace_id) }}</div>
                                            <span class="text-sm text-muted">{{ __('Tasks') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
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
                                    <p class="text-muted mt-3">
                                        {{ __("It's looking like you may have taken a wrong turn. Don't worry... it happens to the best of us. Here's a little tip that might help you get back on track.") }}
                                    </p>
                                    <div class="mt-3">
                                        <a class="btn-return-home badge-blue" href="{{ route('home') }}"><i
                                                class="fas fa-reply"></i> {{ __('Return Home') }}</a>
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
