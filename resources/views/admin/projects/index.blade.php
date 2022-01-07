@extends('layouts.admin')

@section('page-title') {{__('Projects')}} @endsection
@section('content')
    <section class="section">
        @if($projects)
            <div class="row mb-2">
                <div class="col-xl-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-end">
                    <div class="text-sm-right status-filter">
                        <div class="btn-group mb-3">
                            <button type="button" class="btn btn-light active" data-filter="*" data-status="All">{{ __('All')}}</button>
                            <button type="button" class="btn btn-light" data-filter=".Ongoing">{{ __('Ongoing')}}</button>
                            <button type="button" class="btn btn-light" data-filter=".Finished">{{ __('Finished')}}</button>
                            <button type="button" class="btn btn-light" data-filter=".OnHold">{{ __('OnHold')}}</button>
                        </div>
                    </div>
                </div><!-- end col-->
            </div>

            <div class="filters-content">
                <div class="row grid">
                    @foreach ($projects as $project)
                        <div class="col-xl-3 col-lg-4 col-sm-6 All {{ $project->status }}">
                            <div class="card hover-shadow-lg">
                                <div class="card-header border-0 pb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            @if($project->status == 'Finished')
                                                <div class="badge badge-pill badge-success">{{ __('Finished')}}</div>
                                            @elseif($project->status == 'Ongoing')
                                                <div class="badge badge-pill badge-secondary">{{ __('Ongoing')}}</div>
                                            @else
                                                <div class="badge badge-pill badge-warning">{{ __('OnHold')}}</div>
                                            @endif
                                        </h6>
                                    </div>
                                </div>
                                <div class="card-body text-center">
                                    {{-- @if($project->is_active)
                                        <a href="@auth('web'){{route('projects.show',[$currentWorkspace->slug,$project->id])}}@elseauth{{route('client.projects.show',[$currentWorkspace->slug,$project->id])}}@endauth" class="avatar rounded-circle avatar-lg hover-translate-y-n3">
                                            <img alt="{{ $project->name }}" avatar="{{ $project->name }}">
                                        </a>
                                    @else
                                        <a href="#" class="avatar rounded-circle avatar-lg hover-translate-y-n3">
                                            <img alt="{{ $project->name }}" avatar="{{ $project->name }}">
                                        </a>
                                    @endif --}}

                                    {{-- <h5 class="h6 my-4">
                                        @if($project->is_active)
                                            <a href="@auth('web'){{route('projects.show',[$currentWorkspace->slug,$project->id])}}@elseauth{{route('client.projects.show',[$currentWorkspace->slug,$project->id])}}@endauth" title="{{ $project->name }}" class="text-title">{{ $project->name }}</a>
                                        @else
                                            <a href="#" title="{{ __('Locked') }}" class="text-title">{{ $project->name }}</a>
                                        @endif
                                    </h5> --}}
                                    <div class="avatar-group hover-avatar-ungroup mb-3">
                                        @foreach($project->users as $user)
                                            @if($user->pivot->is_active)
                                                <a href="{{route('admin_projects.show',$project->id)}}" class="avatar rounded-circle avatar-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{$user->name}}">
                                                    <img alt="{{$user->name}}" @if($user->avatar) src="{{asset('/storage/avatars/'.$user->avatar)}}" @else avatar="{{ $user->name }}" @endif>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                    <p class="mb-1">
                                    <span class="pr-2 text-nowrap mb-2 d-inline-block small">
                                        <b>{{$project->countTask()}}</b> {{ __('Tasks')}}
                                    </span>
                                    <span class="text-nowrap mb-2 d-inline-block small">
                                        <b>{{$project->countTaskComments()}}</b> {{ __('Comments')}}
                                    </span>
                                    </p>
                                </div>
                                {{-- <div class="card-footer p-0 py-3">
                                    <div class="actions d-flex justify-content-between px-4">

                                        @if($project->is_active)
                                            @auth('web')
                                                @if($currentWorkspace->permission == 'Owner')
                                                    <a href="#" class="action-item" data-ajax-popup="true" data-size="lg" data-title="{{ __('Invite Users') }}" data-url="{{route('projects.invite.popup',[$currentWorkspace->slug,$project->id])}}">
                                                        <i class="fas fa-user-plus"></i>
                                                    </a>
                                                    <a href="#" class="action-item" data-ajax-popup="true" data-size="lg" data-title="{{ __('Edit Project') }}" data-url="{{route('projects.edit',[$currentWorkspace->slug,$project->id])}}">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                    <a href="#" class="action-item" data-ajax-popup="true" data-size="lg" data-title="{{ __('Share to Clients') }}" data-url="{{route('projects.share.popup',[$currentWorkspace->slug,$project->id])}}">
                                                        <i class="fas fa-share-alt"></i>
                                                    </a>
                                                    <a href="#" class="action-item text-danger" data-confirm="{{ __('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="document.getElementById('delete-form-{{$project->id}}').submit();">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                    <form id="delete-form-{{$project->id}}" action="{{ route('projects.destroy',[$currentWorkspace->slug,$project->id]) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @else
                                                    <a href="#" class="action-item text-danger" data-confirm="{{ __('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="document.getElementById('leave-form-{{$project->id}}').submit();">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                    <form id="leave-form-{{$project->id}}" action="{{ route('projects.leave',[$currentWorkspace->slug,$project->id]) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endif
                                            @endauth
                                        @else
                                            <a href="#" class="action-item" title="{{__('Locked')}}">
                                                <i class="fa fa-lock"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    @endforeach
                </div>
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
@endsection

@push('css-page')
@endpush

@push('scripts')
    <script src="{{asset('assets/js/isotope.pkgd.min.js')}}"></script>
    <script>
        $(document).ready(function () {

            $('.status-filter button').click(function () {
                $('.status-filter button').removeClass('active');
                $(this).addClass('active');

                var data = $(this).attr('data-filter');
                $grid.isotope({
                    filter: data
                })
            });

            var $grid = $(".grid").isotope({
                itemSelector: ".All",
                percentPosition: true,
                masonry: {
                    columnWidth: ".All"
                }
            })
        });
    </script>
@endpush
