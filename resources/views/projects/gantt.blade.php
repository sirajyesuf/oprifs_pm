@extends('layouts.admin')

@section('page-title') {{__('Gantt Chart')}} @endsection

@section('multiple-action-button')
    <div class="btn-group mt-1 mr-2" id="change_view" role="group">
        <a href="{{route($is_client.'projects.gantt',[$currentWorkspace->slug,$project->id,'Quarter Day'])}}" class="btn btn-xs btn-info @if($duration == 'Quarter Day')active @endif" data-value="Quarter Day">{{__('Quarter Day')}}</a>
        <a href="{{route($is_client.'projects.gantt',[$currentWorkspace->slug,$project->id,'Half Day'])}}" class="btn btn-xs btn-info @if($duration == 'Half Day')active @endif" data-value="Half Day">{{__('Half Day')}}</a>
        <a href="{{route($is_client.'projects.gantt',[$currentWorkspace->slug,$project->id,'Day'])}}" class="btn btn-xs btn-info @if($duration == 'Day')active @endif" data-value="Day">{{__('Day')}}</a>
        <a href="{{route($is_client.'projects.gantt',[$currentWorkspace->slug,$project->id,'Week'])}}" class="btn btn-xs btn-info @if($duration == 'Week')active @endif" data-value="Week">{{__('Week')}}</a>
        <a href="{{route($is_client.'projects.gantt',[$currentWorkspace->slug,$project->id,'Month'])}}" class="btn btn-xs btn-info @if($duration == 'Month')active @endif" data-value="Month">{{__('Month')}}</a>
    </div>
    @auth('client')
        <a href="{{route('client.projects.show',[$currentWorkspace->slug,$project->id])}}" class="btn-submit">
            <i class="fa fa-reply"></i> {{ __('Back') }}
        </a>
    @elseauth
        <a href="{{route('projects.show',[$currentWorkspace->slug,$project->id])}}" class="btn-submit">
            <i class="fa fa-reply"></i> {{ __('Back') }}
        </a>
    @endauth
@endsection

@section('content')
    <?php $permissions = Auth::user()->getPermission($project->id); ?>

    <section class="section">

        @if($project && $currentWorkspace)
            <div class="row">
                <div class="col-12">
                    <div class="gantt-target"></div>
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
@if($project && $currentWorkspace)
    @push('css-page')
        <link rel="stylesheet" href="{{asset('assets/css/frappe-gantt.css')}}" />
    @endpush
    @push('scripts')
        @php
            $currantLang = basename(App::getLocale());
        @endphp
        <script>
            const month_names = {
                "{{$currantLang}}": [
                    '{{__('January')}}',
                    '{{__('February')}}',
                    '{{__('March')}}',
                    '{{__('April')}}',
                    '{{__('May')}}',
                    '{{__('June')}}',
                    '{{__('July')}}',
                    '{{__('August')}}',
                    '{{__('September')}}',
                    '{{__('October')}}',
                    '{{__('November')}}',
                    '{{__('December')}}'
                ],
                "en": [
                    'January',
                    'February',
                    'March',
                    'April',
                    'May',
                    'June',
                    'July',
                    'August',
                    'September',
                    'October',
                    'November',
                    'December'
                ],
            };
        </script>
        <script src="{{asset('assets/js/frappe-gantt.js')}}"></script>
        <script>
            var tasks = JSON.parse('{!! addslashes(json_encode($tasks)) !!}');
            var gantt_chart = new Gantt(".gantt-target", tasks, {
                custom_popup_html: function(task) {
                    var status_class = 'success';
                    if(task.custom_class == 'medium'){
                        status_class = 'info'
                    }else if(task.custom_class == 'high'){
                        status_class = 'danger'
                    }
                    return `<div class="details-container">
                                <div class="title">${task.name} <span class="badge badge-${status_class} float-right">${task.extra.priority}</span></div>
                                <div class="subtitle">
                                    <b>${task.progress}%</b> {{ __('Progress')}} <br>
                                    <b>${task.extra.comments}</b> {{ __('Comments')}} <br>
                                    <b>{{ __('Duration')}}</b> ${task.extra.duration}
                                </div>
                            </div>
                          `;
                },
                on_click: function (task) {
                    //console.log(task);
                },
                on_date_change: function(task, start, end) {
                    task_id = task.id;
                    start = moment(start);
                    end = moment(end);
                    $.ajax({
                        url:"@auth('client'){{route('client.projects.gantt.post',[$currentWorkspace->slug,$project->id])}}@else{{route('projects.gantt.post',[$currentWorkspace->slug,$project->id])}}@endif",
                        data:{
                            start:start.format('YYYY-MM-DD HH:mm:ss'),
                            end:end.format('YYYY-MM-DD HH:mm:ss'),
                            task_id:task_id,
                        },
                        type:'POST',
                        success:function (data) {

                        },
                        error:function (data) {
                            show_toastr('Error', '{{ __("Some Thing Is Wrong!")}}', 'error');
                        }
                    });
                },
                view_mode: '{{$duration}}',
                language: '{{$currantLang}}'
            });
        </script>
    @endpush
@endif
