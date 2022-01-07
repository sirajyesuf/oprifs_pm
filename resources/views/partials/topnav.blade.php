@php
$unseenCounter = App\Models\ChMessage::where('to_id', Auth::user()->id)
    ->where('seen', 0)
    ->count();
@endphp

<nav class="navbar navbar-main navbar-expand-lg navbar-border n-top-header" id="navbar-main">
    <div class="container-fluid">
        <!-- Brand + Toggler (for mobile devices) -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-main-collapse"
            aria-controls="navbar-main-collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- User's navbar -->
        <div class="navbar-user d-lg-none ml-auto">
            <ul class="navbar-nav flex-row align-items-center">
                <li class="nav-item">
                    <a href="#" class="nav-link nav-link-icon sidenav-toggler" data-action="sidenav-pin"
                        data-target="#sidenav-main"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link nav-link-icon" data-action="omnisearch-open"
                        data-target="#omnisearch"><i class="fas fa-search"></i></a>
                </li>
                <li class="nav-item dropdown dropdown-animate">
                    @if (isset($currentWorkspace) && $currentWorkspace)
                        @auth('web')
                            @php($notifications = Auth::user()->notifications($currentWorkspace->id))

                            <a class="nav-link nav-link-icon @if (count($notifications))beep @endif" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    class="fas fa-bell"></i></a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg dropdown-menu-arrow p-0">
                                <div class="py-3 px-3">
                                    <h5 class="heading h6 mb-0">Notifications</h5>
                                </div>
                                <div class="list-group list-group-flush">
                                    @foreach ($notifications as $notification)
                                        {!! $notification->toHtml() !!}
                                    @endforeach
                                </div>
                                {{-- <div class="py-3 text-center">
                                    <a href="#" class="link link-sm link--style-3">{{ __('View all notifications') }}</a>
                                </div> --}}
                            </div>
                        @endauth
                    @endif
                </li>
                <li class="nav-item dropdown dropdown-animate">
                    <a class="nav-link pr-lg-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <span class="avatar avatar-sm rounded-circle">
                            <img @if (Auth::user()->avatar) src="{{ asset('/storage/avatars/' . Auth::user()->avatar) }}" @else avatar="{{ Auth::user()->name }}" @endif alt="{{ Auth::user()->name }}">
                        </span>
                    </a>
                    @if (Auth::user()->type != 'admin')
                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right dropdown-menu-arrow">
                            <h6 class="dropdown-header px-0 text-center">{{ __('Hi') }}, {{ Auth::user()->name }}
                            </h6>
                            @foreach (Auth::user()->workspace as $workspace)
                                @if ($workspace->is_active)
                                    <a href="@if (isset($currentWorkspace) && $currentWorkspace->id == $workspace->id)#@else @auth('web'){{ route('change-workspace', $workspace->id) }}@elseauth{{ route('client.change-workspace', $workspace->id) }}@endauth @endif" title="{{ $workspace->name }}"
                                        class="dropdown-item">
                                        @if ($currentWorkspace->id == $workspace->id)
                                            <i class="fa fa-check"></i>
                                        @endif
                                        <span>{{ $workspace->name }}</span>
                                        @if (isset($workspace->pivot->permission))
                                            @if ($workspace->pivot->permission == 'Owner')
                                                <span
                                                    class="badge badge-primary">{{ __($workspace->pivot->permission) }}</span>
                                            @else
                                                <span class="badge badge-secondary">{{ __('Shared') }}</span>
                                            @endif
                                        @endif
                                    </a>
                                @else
                                    <a href="#" class="dropdown-item " title="{{ __('Locked') }}">
                                        <i class="fa fa-lock"></i>
                                        <span>{{ $workspace->name }}</span>
                                        @if (isset($workspace->pivot->permission))
                                            @if ($workspace->pivot->permission == 'Owner')
                                                <span
                                                    class="badge badge-primary">{{ __($workspace->pivot->permission) }}</span>
                                            @else
                                                <span class="badge badge-secondary">{{ __('Shared') }}</span>
                                            @endif
                                        @endif
                                    </a>
                                @endif
                            @endforeach

                            @if (Auth::user()->type == 'manager' && isset($currentWorkspace) && $currentWorkspace)
                                <div class="dropdown-divider"></div>
                            @endif

                            @auth('web')
                                @if (Auth::user()->type == 'manager')
                                    <a href="#" class="dropdown-item" data-toggle="modal"
                                        data-target="#modelCreateWorkspace">
                                        <i class="fa fa-plus"></i>
                                        <span>{{ __('Create New Workspace') }}</span>
                                    </a>
                                @endif
                            @endauth

                            @if (Auth::user()->type == 'manager' && isset($currentWorkspace) && $currentWorkspace)
                                @auth('web')
                                    @if ( Auth::user()->id == $currentWorkspace->created_by)
                                        {{-- <a href="#" class="dropdown-item"
                                            data-confirm="{{ __('Are You Sure?') . '|' . __('This action can not be undone. Do you want to continue?') }}"
                                            data-confirm-yes="document.getElementById('remove-workspace-form').submit();">
                                            <i class="fa fa-trash"></i>
                                            <span>{{ __('Remove Me From This Workspace') }}</span>
                                        </a>
                                        <form id="remove-workspace-form"
                                            action="{{ route('delete-workspace', ['id' => $currentWorkspace->id]) }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form> --}}
                                    @else
                                        {{-- <a href="#" class="dropdown-item"
                                            data-confirm="{{ __('Are You Sure?') . '|' . __('This action can not be undone. Do you want to continue?') }}"
                                            data-confirm-yes="document.getElementById('remove-workspace-form').submit();">
                                            <i class="fa fa-trash"></i>
                                            <span>{{ __('Leave Me From This Workspace') }}</span>
                                        </a>
                                        <form id="remove-workspace-form"
                                            action="{{ route('leave-workspace', ['id' => $currentWorkspace->id]) }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form> --}}
                                    @endif
                                @endauth
                            @endif
                            @if (Auth::user()->type == 'manager')
                                <div class="dropdown-divider"></div>
                            @endif

                            <a href="@auth('web'){{ route('users.my.account') }}@elseauth{{ route('client.users.my.account') }}@endauth"
                                    class="dropdown-item">
                                    <i class="fa fa-user-circle"></i> <span>{{ __('My Profile') }}</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item text-danger"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> <span>{{ __('Logout') }}</span>
                                </a>
                                <form id="logout-form"
                                    action="@auth('web'){{ route('logout') }}@elseauth{{ route('client.logout') }}@endauth"
                                        method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            @endif

                        </li>
                    </ul>
                </div>
                <!-- Navbar nav -->
                <div class="collapse navbar-collapse navbar-collapse-fade" id="navbar-main-collapse">
                    <ul class="navbar-nav align-items-center d-none d-lg-flex">
                        <li class="nav-item">
                            <a href="#" class="nav-link nav-link-icon sidenav-toggler" data-action="sidenav-pin"
                                data-target="#sidenav-main"><i class="fas fa-bars"></i></a>
                        </li>
                        <li class="nav-item dropdown dropdown-animate">
                            <a class="nav-link pr-lg-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <div class="media media-pill align-items-center">
                                    <span class="avatar rounded-circle">
                                        <img @if (Auth::user()->avatar) src="{{ asset('/storage/avatars/' . Auth::user()->avatar) }}" @else avatar="{{ Auth::user()->name }}" @endif alt="{{ Auth::user()->name }}">
                                    </span>
                                    <div class="ml-2 d-none d-lg-block">
                                        <span class="mb-0 text-sm font-weight-bold">{{ Auth::user()->name }}</span>
                                    </div>
                                </div>
                            </a>

                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-left dropdown-menu-arrow">
                                <h6 class="dropdown-header px-0 text-center">{{ __('Hi') }}, {{ Auth::user()->name }}
                                </h6>
                                @if (Auth::user()->type != 'admin')
                                    @foreach (Auth::user()->workspace as $workspace)
                                        @if ($workspace->is_active)
                                            <a href="@if ($currentWorkspace->id == $workspace->id)#@else @auth('web'){{ route('change-workspace', $workspace->id) }}@elseauth{{ route('client.change-workspace', $workspace->id) }}@endauth @endif" title="{{ $workspace->name }}"
                                                class="dropdown-item">
                                                @if ($currentWorkspace->id == $workspace->id)
                                                    <i class="fa fa-check"></i>
                                                @endif
                                                <span>{{ $workspace->name }}</span>
                                                @if (isset($workspace->pivot->permission))
                                                    @if ($workspace->pivot->permission == 'Owner')
                                                        <span
                                                            class="badge badge-primary">{{ __($workspace->pivot->permission) }}</span>
                                                    @else
                                                        <span class="badge badge-secondary">{{ __('Shared') }}</span>
                                                    @endif
                                                @endif
                                            </a>
                                        @else
                                            <a href="#" class="dropdown-item " title="{{ __('Locked') }}">
                                                <i class="fa fa-lock"></i>
                                                <span>{{ $workspace->name }}</span>
                                                @if (isset($workspace->pivot->permission))
                                                    @if ($workspace->pivot->permission == 'Owner')
                                                        <span
                                                            class="badge badge-primary">{{ __($workspace->pivot->permission) }}</span>
                                                    @else
                                                        <span class="badge badge-secondary">{{ __('Shared') }}</span>
                                                    @endif
                                                @endif
                                            </a>
                                        @endif
                                    @endforeach
                                @endif


                                @if (isset($currentWorkspace) && $currentWorkspace)
                                    <div class="dropdown-divider"></div>
                                @endif

                                @auth('web')
                                    @if (Auth::user()->type == 'manager')
                                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modelCreateWorkspace">
                                            <i class="fa fa-plus"></i>
                                            <span>{{ __('Create New Workspace') }}</span>
                                        </a>
                                    @endif
                                @endauth

                                {{-- @if (isset($currentWorkspace) && $currentWorkspace)
                                    @auth('web')
                                        @if (Auth::user()->id == $currentWorkspace->created_by)
                                            <a href="#" class="dropdown-item"
                                                data-confirm="{{ __('Are You Sure?') . '|' . __('This action can not be undone. Do you want to continue?') }}"
                                                data-confirm-yes="document.getElementById('remove-workspace-form').submit();">
                                                <i class="fa fa-trash"></i>
                                                <span>{{ __('Remove Me From This Workspace') }}</span>
                                            </a>
                                            <form id="remove-workspace-form"
                                                action="{{ route('delete-workspace', ['id' => $currentWorkspace->id]) }}"
                                                method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @else
                                            <a href="#" class="dropdown-item"
                                                data-confirm="{{ __('Are You Sure?') . '|' . __('This action can not be undone. Do you want to continue?') }}"
                                                data-confirm-yes="document.getElementById('remove-workspace-form').submit();">
                                                <i class="fa fa-trash"></i>
                                                <span>{{ __('Leave Me From This Workspace') }}</span>
                                            </a>
                                            <form id="remove-workspace-form"
                                                action="{{ route('leave-workspace', ['id' => $currentWorkspace->id]) }}"
                                                method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    @endauth
                                @endif --}}
                                @if (Auth::user()->type == 'user')
                                    <div class="dropdown-divider"></div>
                                @endif

                                <a href="@auth('web'){{ route('users.my.account') }}@elseauth{{ route('client.users.my.account') }}@endauth"
                                        class="dropdown-item">
                                        <i class="fa fa-user-circle"></i> <span>{{ __('My Profile') }}</span>
                                    </a>


                                    @if (Auth::user()->type == 'user' || Auth::user()->type == 'manager')
                                        <div class="dropdown-divider"></div>
                                        <a href="{{ url('chats') }}" class="dropdown-item">
                                            <i class="fas fa-comment"></i>
                                            <span>{{ __('Messages') }}</span>
                                        </a>
                                    @endif


                                    <div class="dropdown-divider"></div>
                                    <a href="#" class="dropdown-item text-danger"
                                        onclick="event.preventDefault();document.getElementById('logout-form1').submit();">
                                        <i class="fas fa-sign-out-alt"></i> <span>{{ __('Logout') }}</span>
                                    </a>
                                    <form id="logout-form1"
                                        action="@auth('web'){{ route('logout') }}@elseauth{{ route('client.logout') }}@endauth"
                                            method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </li>

                                @if (\Auth::user()->type == 'user' || Auth::user()->type == 'manager')
                                    <li class="nav-item">
                                        <a href="{{ url('chats') }}" class="">
                                            <span><i class="fas fa-comment" style="font-size: 21px"></i></span>
                                            <span class="badge badge-danger badge-circle badge-btn custom_messanger_counter">
                                                {{ $unseenCounter }}
                                            </span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                            <ul class="navbar-nav ml-lg-auto align-items-lg-center">
                                @if (isset($currentWorkspace) && $currentWorkspace)
                                    @auth('web')
                                        @php($messages = Auth::user()->unread($currentWorkspace->id, Auth::user()->id))

                                        {{-- <li class="nav-item dropdown dropdown-animate">
                                            <a class="nav-link nav-link-icon message-toggle @if (count($messages)) beep @endif" href="#" role="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                                    class="fas fa-comment-alt"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg dropdown-menu-arrow p-0">
                                                <div class="py-3 px-3">
                                                    <h5 class="heading h6 mb-0">
                                                        {{ __('Messages') }}
                                                        <div class="float-right">
                                                            <a href="#" class="mark_all_as_read_message">{{ __('Mark All As Read') }}</a>
                                                        </div>
                                                    </h5>
                                                </div>
                                                <div class="list-group list-group-flush dropdown-list-message">
                                                </div>
                                                <div class="py-3 text-center">
                                                    <a href="{{ route('chats') }}"
                                                        class="link link-sm link--style-3">{{ __('View All') }}
                                                        <i class="fa fa-chevron-right"></i></a>
                                                </div>
                                            </div>
                                        </li> --}}
                                        {{-- <li class="nav-item dropdown dropdown-animate">
                                            @php($notifications = Auth::user()->notifications($currentWorkspace->id))

                                            <a class="nav-link nav-link-icon notification-toggle @if (count($notifications)) beep @endif" href="#"
                                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                                    class="fas fa-bell"></i></a>
                                            <div
                                                class="dropdown-menu dropdown-menu-right dropdown-menu-lg dropdown-menu-arrow p-0 notification-dropdown">
                                                <div class="py-3 px-3">
                                                    <h5 class="heading h6 mb-0">
                                                        {{ __('Notifications') }}
                                                        <div class="float-right">
                                                            <a href="#" class="mark_all_as_read">{{ __('Mark All As Read') }}</a>
                                                        </div>
                                                    </h5>
                                                </div>
                                                <div class="list-group list-group-flush is-end dropdown-list-icons">
                                                    @foreach ($notifications as $notification)
                                                        {!! $notification->toHtml() !!}
                                                    @endforeach
                                                </div>
                                                <div class="py-3 text-center">
                                                    <a href="#" class="link link-sm link--style-3">View all notifications</a>
                                                </div>
                                            </div>
                                        </li> --}}
                                    @endauth
                                @endif
                                {{-- @php($currantLang = basename(App::getLocale()))
                                <li class="nav-item dropdown dropdown-list-toggle">
                                    <a href="#" data-toggle="dropdown" class="nav-link nav-link-icon">
                                        <span class="align-middle"><i
                                                class="fas fa-globe-europe mr-2"></i>{{ Str::upper($currantLang) }}</span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right dropdown-menu-arrow mr-3">
                                        @if (\Auth::user()->type == 'admin')
                                            @foreach (\App\Models\Utility::languages() as $lang)
                                                <a href="{{ route('change_lang_admin', $lang) }}"
                                                    class="dropdown-item {{ $currantLang == $lang ? 'text-danger' : '' }}">
                                                    <span class="small">{{ Str::upper($lang) }}</span>
                                                </a>
                                            @endforeach
                                        @elseif(isset($currentWorkspace) && $currentWorkspace && $currentWorkspace->permission ==
                                            'Owner')
                                            @foreach ($currentWorkspace->languages() as $lang)
                                                <a href="{{ route('change_lang_workspace', [$currentWorkspace->id, $lang]) }}"
                                                    class="dropdown-item {{ $currantLang == $lang ? 'text-danger' : '' }}">
                                                    <span class="small">{{ Str::upper($lang) }}</span>
                                                </a>
                                            @endforeach
                                        @endif
                                    </div>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                </nav>
