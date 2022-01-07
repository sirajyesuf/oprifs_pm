@extends('layouts.admin')

@section('page-title') {{__('Project Detail')}} @endsection

@php
    // $permissions = Auth::user()->getPermission($project->id);
    $client_keyword = Auth::user()->getGuard() == 'client' ? 'client.' : '';
@endphp

@section('multiple-action-button')
        {{-- <a href="{{route('admin_projects.projects_time_sheet',$project->id)}}" class="btn btn-xs btn-white btn-icon-only width-auto">{{ __('Timesheet')}}</a> --}}
        {{-- <a href="{{route('admin_projects.project_gant_chart',$project->id)}}" class="btn btn-xs btn-white btn-icon-only width-auto">{{ __('Gantt Chart')}}</a> --}}
        {{-- <a href="{{route('admin_projects.taskBoard',$project->id)}}" class="btn btn-xs btn-white btn-icon-only width-auto">{{ __('Task Board')}}</a> --}}
        {{-- <a href="{{route('admin_projects.bugReport',$project->id)}}" class="btn btn-xs btn-white btn-icon-only width-auto">{{ __('Bug Report')}}</a> --}}
@endsection

@section('content')
    <section class="section">
        @if($project && $currentWorkspace)
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card project-detail-box">
                                <div class="card-header pb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="mb-0">
                                                {{$project->name}}
                                                @if($project->status == 'Finished')
                                                    <div class="badge badge-xs badge-success">{{ __('Finished')}}</div>
                                                @elseif($project->status == 'Ongoing')
                                                    <div class="badge badge-xs badge-secondary">{{ __('Ongoing')}}</div>
                                                @else
                                                    <div class="badge badge-xs badge-warning">{{ __('OnHold')}}</div>
                                                @endif
                                            </h6>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            @if(!$project->is_active)
                                                <a href="#" class="delete-icon" title="{{__('Locked')}}">
                                                    <i class="fas fa-lock"></i>
                                                </a>
                                            @else
                                                {{-- @auth('web')
                                                    @if($currentWorkspace->permission == 'Owner')
                                                        <a href="#" class="edit-icon" data-url="{{ route('projects.edit',[$currentWorkspace->slug,$project->id]) }}" data-ajax-popup="true" data-title="{{__('Edit Project')}}">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </a>
                                                        <a href="#" class="delete-icon" data-confirm="{{ __('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="document.getElementById('delete-form-{{ $project->id }}').submit();"><i class="fas fa-trash"></i></a>
                                                        <form id="delete-form-{{$project->id}}" action="{{ route('projects.destroy',[$currentWorkspace->slug,$project->id]) }}" method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    @else
                                                        <a href="#" class="delete-icon" data-confirm="{{ __('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="document.getElementById('leave-form-{{ $project->id }}').submit();"><i class="fas fa-sign-out"></i></a>
                                                        <form id="leave-form-{{$project->id}}" action="{{ route('projects.leave',[$currentWorkspace->slug,$project->id]) }}" method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    @endif
                                                @endauth --}}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-4 flex-grow-1">
                                    <p class="text-sm mb-0">
                                        {{$project->description}}
                                    </p>
                                </div>
                                <div class="card-footer py-0">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <small>{{ __('Start Date') }}:</small>
                                                    <div class="h6 mb-0">{{App\Models\Utility::dateFormat($project->start_date)}}</div>
                                                </div>
                                                <div class="col">
                                                    <small>{{ __('Due Date') }}:</small>
                                                    <div class="h6 mb-0">{{App\Models\Utility::dateFormat($project->end_date)}}</div>
                                                </div>
                                                <div class="col-auto">
                                                    <small>{{ __('Total Members') }}:</small>
                                                    <div class="h6 mb-0">{{ (int) $project->users->count() + (int) $project->clients->count() }}</div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card card-stats">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col">
                                            <h6 class="text-muted mb-1">{{ __('Days left') }}</h6>
                                            <span class="h6 font-weight-bold mb-0 ">{{ $daysleft }}</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon bg-gradient-info text-white rounded-circle icon-shape">
                                                <i class="fas fas fa-calendar-day"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-md-3">
                            <div class="card card-stats">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col">
                                            <h6 class="text-muted mb-1">{{ __('Budget') }}</h6>
                                            <span class="h6 font-weight-bold mb-0 ">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}{{ number_format($project->budget) }}</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon bg-gradient-success text-white rounded-circle icon-shape">
                                                <i class="fas fa-money-bill-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-md-3">
                            <div class="card card-stats">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col">
                                            <h6 class="text-muted mb-1">{{ __('Total task') }}</h6>
                                            <span class="h6 font-weight-bold mb-0 ">{{ $project->countTask() }}</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon bg-gradient-danger text-white rounded-circle icon-shape">
                                                <i class="fas fa-check-double"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card card-stats">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col">
                                            <h6 class="text-muted mb-1">{{__('Comments')}}</h6>
                                            <span class="h6 font-weight-bold mb-0 ">{{$project->countTaskComments()}}</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon bg-gradient-success text-white rounded-circle icon-shape">
                                                <i class="fas fa-comments"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card ">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">{{ __('Team Members') }} ({{count($project->users)}})</h6>
                                        <div class="text-right">
                                            @if($currentWorkspace->permission == 'Owner')
                                                <a href="#" class="btn btn-xs btn-info" data-ajax-popup="true" data-title="{{ __('Invite') }}" data-url="{{route('projects.invite.popup',[$currentWorkspace->slug,$project->id])}}"><i class="fas fa-paper-plane"></i></a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group list-group-flush">
                                    @foreach($project->users as $user)
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <a href="#" class="avatar rounded-circle">
                                                        <img @if($user->avatar) src="{{asset('/storage/avatars/'.$user->avatar)}}" @else avatar="{{ $user->name }}" @endif>
                                                    </a>
                                                </div>
                                                <div class="col ml-n2">
                                                    <a href="#" class="d-block h6 small mb-0">{{$user->name}}</a>
                                                    <small>{{$user->email}} <span class="bullet"></span> <span class="text-primary ">{{(int) count($project->user_done_tasks($user->id))}} / {{(int) count($project->user_tasks($user->id))}}</span></small>
                                                </div>
                                                <div class="col-auto">
                                                    @auth('web')
                                                        @if($currentWorkspace->permission == 'Owner' && $user->id != Auth::id())
                                                            <a href="#" class="edit-icon" data-ajax-popup="true" data-size="lg" data-title="{{__('Edit Permission')}}" data-url="{{route('projects.user.permission',[$currentWorkspace->slug,$project->id,$user->id])}}"><i class="fa fa-lock"></i></a>

                                                            <a href="#" class="delete-icon" data-confirm="{{ __('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="document.getElementById('delete-user-{{$user->id}}').submit();"><i class="fas fa-trash"></i></a>
                                                            <form id="delete-user-{{$user->id}}" action="{{ route('projects.user.delete',[$currentWorkspace->slug,$project->id,$user->id]) }}" method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        @endif
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">{{__('Clients')}} ({{count($project->clients)}})</h6>
                                        <div class="text-right">
                                            @if($currentWorkspace->permission == 'Owner')
                                                <a href="#" class="btn btn-xs btn-info" data-ajax-popup="true" data-title="{{ __('Share to Clients') }}" data-url="{{route('projects.invite.popup',[$currentWorkspace->slug,$project->id])}}"><i class="fas fa-share-alt"></i></a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group list-group-flush">
                                    @foreach($project->clients as $client)
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <a href="#" class="avatar rounded-circle">
                                                        <img @if($client->avatar) src="{{asset('/storage/avatars/'.$client->avatar)}}" @else avatar="{{ $client->name }}" @endif>
                                                    </a>
                                                </div>
                                                <div class="col ml-n2">
                                                    <a href="#" class="d-block h6 small mb-0">{{$client->name}}</a>
                                                    <small>{{$client->email}}</small>
                                                </div>
                                                <div class="col-auto">
                                                    @auth('web')
                                                        @if($currentWorkspace->permission == 'Owner')
                                                            <a href="#" class="edit-icon" data-ajax-popup="true" data-size="lg" data-title="{{__('Edit Permission')}}" data-url="{{route('projects.client.permission',[$currentWorkspace->slug,$project->id,$client->id])}}"><i class="fa fa-lock"></i></a>
                                                            <a href="#" class="delete-icon" data-confirm="{{ __('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="document.getElementById('delete-client-{{$client->id}}').submit();"><i class="fas fa-trash"></i></a>
                                                            <form id="delete-client-{{$client->id}}" action="{{ route('projects.client.delete',[$currentWorkspace->slug,$project->id,$client->id]) }}" method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        @endif
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-md-12">

                            @if((isset($permissions) && in_array('show milestone', $permissions)) || $currentWorkspace->permission == 'Owner')

                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{__('Milestones')}} ({{count($project->milestones)}})</h6>
                                            <div class="text-right">
                                                @if((isset($permissions) && in_array('create milestone',$permissions)) || $currentWorkspace->permission == 'Owner')
                                                    <a href="#" class="btn btn-xs btn-info" data-ajax-popup="true" data-title="{{ __('Create Milestone') }}" data-url="{{route($client_keyword.'projects.milestone',[$currentWorkspace->slug,$project->id])}}"><i class="fas fa-plus"></i></a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group list-group-flush">
                                        @foreach($project->milestones as $key => $milestone)
                                            <div class="list-group-item">
                                                <div class="row align-items-center">
                                                    <div class="col">
                                                        <a href="#" class="d-block font-weight-500 mb-0" data-ajax-popup="true" data-title="{{ __('Milestone Details') }}" data-url="{{route($client_keyword.'projects.milestone.show',[$currentWorkspace->slug,$milestone->id])}}">
                                                            {{$milestone->title}}
                                                        </a>
                                                        <small>
                                                            @if($milestone->status == 'complete')
                                                                <label class="badge badge-success">{{__('Complete')}}</label>
                                                            @else
                                                                <label class="badge badge-warning">{{__('Incomplete')}}</label>
                                                            @endif
                                                        </small>
                                                    </div>
                                                    <div class="col small">
                                                        <strong>{{ __('Milestone Cost') }}:</strong>
                                                        {{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$' }}{{$milestone->cost}}
                                                    </div>
                                                    <div class="col-auto">
                                                        @if($currentWorkspace->permission == 'Owner')
                                                            <a href="#" class="edit-icon" data-ajax-popup="true" data-size="lg" data-title="{{__('Edit Milestone')}}" data-url="{{route('projects.milestone.edit',[$currentWorkspace->slug,$milestone->id])}}"><i class="fas fa-pencil-alt"></i></a>
                                                            <a href="#" class="delete-icon" data-confirm="{{ __('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="document.getElementById('delete-form1-{{$milestone->id}}').submit();"><i class="fas fa-trash"></i></a>
                                                            <form id="delete-form1-{{$milestone->id}}" action="{{ route('projects.milestone.destroy',[$currentWorkspace->slug,$milestone->id]) }}" method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        @elseif(isset($permissions))
                                                            @if(in_array('edit milestone',$permissions))
                                                                <a href="#" class="edit-icon" data-ajax-popup="true" data-size="lg" data-title="{{__('Edit Milestone')}}" data-url="{{route($client_keyword.'projects.milestone.edit',[$currentWorkspace->slug,$milestone->id])}}"><i class="fas fa-pencil-alt"></i></a>
                                                            @endif
                                                            @if(in_array('delete milestone',$permissions))
                                                                <a href="#" class="delete-icon" data-confirm="{{ __('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="document.getElementById('delete-form1-{{$milestone->id}}').submit();"><i class="fas fa-trash"></i></a>
                                                                <form id="delete-form1-{{$milestone->id}}" action="{{ route($client_keyword.'projects.milestone.destroy',[$currentWorkspace->slug,$milestone->id]) }}" method="POST" style="display: none;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if((isset($permissions) && in_array('show uploading',$permissions)) || $currentWorkspace->permission == 'Owner' || $currentWorkspace->permission == 'Member')
                                <div class="card author-box card-primary">
                                    <div class="card-body p-4">
                                        <div class="author-box-name form-control-label mb-4">
                                            {{__('Files')}}
                                        </div>
                                        <div class="col-md-12 dropzone browse-file" id="dropzonewidget">
                                            <div class="dz-message" data-dz-message>
                                        <span>
                                            @if(Auth::user()->getGuard() == 'client')
                                                {{__('No files available')}}
                                            @else
                                                {{__('Drop files here to upload')}}
                                            @endif
                                        </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    {{-- <div class="card card-primary">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="float-left">
                                    {{ __('Progress') }}
                                    <small>({{ __('Last Week Tasks') }})</small>
                                </h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="line-chart-example"></div>
                        </div>
                    </div> --}}

                    {{-- <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ __('Activity') }}</h6>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-3">
                            <div class="timeline timeline-one-side top-10-scroll" data-timeline-content="axis" data-timeline-axis-style="dashed">

                                @if((isset($permissions) && in_array('show activity',$permissions)) || $currentWorkspace->permission == 'Owner')
                                    @foreach($project->activities as $activity)
                                        <div class="timeline-block">
                                            @if($activity->log_type == 'Upload File')
                                                <span class="timeline-step timeline-step-sm bg-primary border-primary text-white"> <i class="fas fa-file"></i></span>
                                            @elseif($activity->log_type == 'Create Milestone')
                                                <span class="timeline-step timeline-step-sm bg-info border-info text-white"> <i class="fas fa-cubes"></i></span>
                                            @elseif($activity->log_type == 'Create Task')
                                                <span class="timeline-step timeline-step-sm bg-success border-success text-white"> <i class="fas fa-tasks"></i></span>
                                            @elseif($activity->log_type == 'Create Bug')
                                                <span class="timeline-step timeline-step-sm bg-warning border-warning text-white"> <i class="fas fa-bug"></i></span>
                                            @elseif($activity->log_type == 'Move' || $activity->log_type == 'Move Bug')
                                                <span class="timeline-step timeline-step-sm bg-danger border-danger text-white"> <i class="fas fa-align-justify"></i></span>
                                            @elseif($activity->log_type == 'Create Invoice')
                                                <span class="timeline-step timeline-step-sm bg-dark border-bg-dark text-white"> <i class="fas fa-file-invoice"></i></span>
                                            @elseif($activity->log_type == 'Invite User')
                                                <span class="timeline-step timeline-step-sm bg-success border-success text-white"> <i class="fas fa-plus"></i></span>
                                            @elseif($activity->log_type == 'Share with Client')
                                                <span class="timeline-step timeline-step-sm bg-info border-info text-white"> <i class="fas fa-share"></i></span>
                                            @elseif($activity->log_type == 'Create Timesheet')
                                                <span class="timeline-step timeline-step-sm bg-success border-success text-white"> <i class="fas fa-clock-o"></i></span>
                                            @endif
                                            <div class="timeline-content">
                                                <span class="text-muted text-sm">{{ $activity->log_type }}</span>
                                                <a href="#" class="d-block h6 text-sm mb-0">{!! $activity->getRemark() !!}</a>
                                                <small><i class="fas fa-clock mr-1"></i>{{ $activity->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div> --}}
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
    <link rel="stylesheet" href="{{asset('assets/css/dropzone.min.css')}}">
@endpush
@push('scripts')
    <script>
        $(document).ready(function () {
            if ($(".top-10-scroll").length) {
                $(".top-10-scroll").css({
                    "max-height": 630
                }).niceScroll();
            }
        });
        var taskAreaOptions = {
            series: [
                    @foreach($chartData['stages'] as $id => $name)
                {
                    name: "{{ __($name)}}",
                    data: {!! json_encode($chartData[$id]) !!}
                },
                @endforeach
            ],
            chart: {
                height: 350,
                type: 'line',
                dropShadow: {
                    enabled: true,
                    color: '#000',
                    top: 18,
                    left: 7,
                    blur: 10,
                    opacity: 0.2
                },
                toolbar: {
                    show: false
                }
            },
            colors: {!! json_encode($chartData['color']) !!},
            dataLabels: {
                enabled: true,
            },
            stroke: {
                curve: 'smooth'
            },
            title: {
                text: '',
                align: 'left'
            },
            grid: {
                borderColor: '#e7e7e7',
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            markers: {
                size: 1
            },
            xaxis: {
                categories: {!! json_encode($chartData['label']) !!},
                title: {
                    text: '{{ __("Days") }}'
                }
            },
            yaxis: {
                title: {
                    text: '{{ __("Tasks") }}'
                },

            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                floating: true,
                offsetY: -25,
                offsetX: -5
            }
        };

        setTimeout(function () {
            var taskAreaChart = new ApexCharts(document.querySelector("#line-chart-example"), taskAreaOptions);
            taskAreaChart.render();
        }, 100);

    </script>

    <script src="{{asset('assets/js/dropzone.min.js')}}"></script>
    <script>
        Dropzone.autoDiscover = false;
        myDropzone = new Dropzone("#dropzonewidget", {
            maxFiles: 20,
            maxFilesize: 20,
            parallelUploads: 1,
            acceptedFiles: ".jpeg,.jpg,.png,.gif,.svg,.pdf,.txt,.doc,.docx,.zip,.rar",
            url: "{{route('projects.file.upload',[$currentWorkspace->slug,$project->id])}}",
            success: function (file, response) {
                if (response.is_success) {
                    dropzoneBtn(file, response);
                } else {
                    myDropzone.removeFile(file);
                    toastr('{{__('Error')}}', response.error, 'error');
                }
            },
            error: function (file, response) {
                myDropzone.removeFile(file);
                if (response.error) {
                    toastr('{{__('Error')}}', response.error, 'error');
                } else {
                    toastr('{{__('Error')}}', response, 'error');
                }
            }
        });
        myDropzone.on("sending", function (file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
            formData.append("project_id", {{$project->id}});
        });

        @if(isset($permisions) && in_array('show uploading',$permisions))
        $(".dz-hidden-input").prop("disabled",true);
        myDropzone.removeEventListeners();
        @endif

        function dropzoneBtn(file, response) {

            var html = document.createElement('div');

            var download = document.createElement('a');
            download.setAttribute('href', response.download);
            download.setAttribute('class', "edit-icon");
            download.setAttribute('data-toggle', "tooltip");
            download.setAttribute('data-original-title', "{{__('Download')}}");
            download.innerHTML = "<i class='fas fa-download'></i>";
            html.appendChild(download);

                @if(isset($permisions) && in_array('show uploading',$permisions))
                @else
            var del = document.createElement('a');
            del.setAttribute('href', response.delete);
            del.setAttribute('class', "delete-icon");
            del.setAttribute('data-toggle', "tooltip");
            del.setAttribute('data-original-title', "{{__('Delete')}}");
            del.innerHTML = "<i class='fas fa-trash'></i>";

            del.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm("Are you sure ?")) {
                    var btn = $(this);
                    $.ajax({
                        url: btn.attr('href'),
                        type: 'DELETE',
                        success: function (response) {
                            if (response.is_success) {
                                btn.closest('.dz-image-preview').remove();
                            } else {
                                toastr('{{__('Error')}}', response.error, 'error');
                            }
                        },
                        error: function (response) {
                            response = response.responseJSON;
                            if (response.is_success) {
                                toastr('{{__('Error')}}', response.error, 'error');
                            } else {
                                toastr('{{__('Error')}}', response, 'error');
                            }
                        }
                    })
                }
            });
            html.appendChild(del);
            @endif

            file.previewTemplate.appendChild(html);
        }

        @php($files = $project->files)
        @foreach($files as $file)

        @php($storage_file = storage_path('project_files/'.$file->file_path))
        // Create the mock file:
        var mockFile = {name: "{{$file->file_name}}", size: {{ file_exists($storage_file) ? filesize($storage_file) : 0 }} };
        // Call the default addedfile event handler
        myDropzone.emit("addedfile", mockFile);
        // And optionally show the thumbnail of the file:
        myDropzone.emit("thumbnail", mockFile, "{{asset('storage/project_files/'.$file->file_path)}}");
        myDropzone.emit("complete", mockFile);

        dropzoneBtn(mockFile, {download: "{{route($client_keyword.'projects.file.download',[$currentWorkspace->slug,$project->id,$file->id])}}", delete: "{{route('projects.file.delete',[$currentWorkspace->slug,$project->id,$file->id])}}"});

        @endforeach
    </script>

@endpush
