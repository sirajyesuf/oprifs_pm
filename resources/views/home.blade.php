@extends('layouts.admin')

@section('page-title') {{__('Dashboard')}} @endsection

@section('content')

    <section class="section">
        @if($currentWorkspace)

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
                        <div class="left-card">
                            <div class="icon-box bg-success"><i class="fas fa-users"></i></div>
                            <h4>{{ __('Total') }} <span>{{ __('Members') }}</span></h4>
                        </div>
                        <div class="number-icon">{{$totalMembers}}</div>
                    </div>
                    <img src="{{ asset('assets/img/dot-icon.png') }}" class="dotted-icon">
                </div>
                {{-- <div class="col-lg-3 col-md-3 col-sm-6">
                    <div class="card card-box">
                        <div class="left-card">
                            <div class="icon-box bg-danger"><i class="fas fa-bug"></i></div>
                            <h4>{{ __('Total') }} <span>{{ __('Bugs') }}</span></h4>
                        </div>
                        <div class="number-icon">{{$totalBugs}}</div>
                    </div>
                    <img src="{{ asset('assets/img/dot-icon.png') }}" class="dotted-icon">
                </div> --}}
            </div>

            {{-- <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="float-left">
                                {{ __('Tasks Overview') }}
                                <small class="d-block mt-2">{{ __('Last Week Tasks')}}</small>
                            </h6>
                        </div>
                        <div class="card-body py-0">
                            <div class="scrollbar-inner">
                                <div id="task-area-chart" class="chartjs-render-monitor" height="210"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="row">
                {{-- <div class="col-xl-4">
                    <div class="card animated">
                        <div class="card-header">
                            <h6>{{ __('Project Status') }}</h6>
                        </div>
                        <div class="card-body">

                            <div class="chartjs-chart">
                                <div id="project-status-chart"></div>
                            </div>

                            <div class="row text-center mt-2 py-2">

                                @foreach($arrProcessPer as $index => $value)

                                    <div class="col-4">
                                        <i class="fas fa-chart {{$arrProcessClass[$index]}} mt-3 h3"></i>
                                        <h6 class="font-weight-bold">
                                            <span>{{$value}}%</span>
                                        </h6>
                                        <p class="text-muted mb-0">{{__($arrProcessLabel[$index])}}</p>
                                    </div>

                                @endforeach

                            </div>
                        </div>
                    </div>
                </div> --}}

                <div class="col-xl-8">
                    <div class="card animated">
                        <div class="card-header">
                            <h6 class="float-left">
                                {{ __('Tasks') }}
                                <small class="d-block mt-2"><b>{{$completeTask}}</b> {{ __('Tasks completed out of')}} {{$totalTask}}</small>
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover mb-0 animated">
                                    <tbody>
                                    @foreach($tasks as $task)
                                        <tr>
                                            <td>
                                                <div class="font-14 my-1"><a href="{{route('projects.task.board',[$currentWorkspace->slug,$task->project_id])}}" class="text-body">{{$task->title}}</a></div>

                                                @php($due_date = '<span class="text-'.($task->due_date < date('Y-m-d') ? 'danger' : 'success').'">'.date('Y-m-d', strtotime($task->due_date)).'</span> ')

                                                <span class="text-muted font-13">{{ __('Due Date') }} : {!! $due_date !!}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted font-13">{{ __('Status') }}</span> <br/>
                                                @if($task->complete=='1')
                                                    <span class="badge badge-success">{{__($task->status)}}</span>
                                                @else
                                                    <span class="badge badge-primary">{{__($task->status)}}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-muted font-13">{{ __('Project') }}</span>
                                                <div class="font-14 mt-1 font-weight-normal">{{$task->project->name}}</div>
                                            </td>
                                            @if($currentWorkspace->permission == 'Owner' || Auth::user()->getGuard() == 'client')
                                                <td>
                                                    <span class="text-muted font-13">{{ __('Assigned to') }}</span>
                                                    <div class="font-14 mt-1 font-weight-normal">
                                                        @foreach($task->users() as $user)
                                                            <span class="badge badge-secondary">{{ isset($user->name) ? $user->name : '-' }}</span>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-0 mt-3 text-center text-white bg-info">
                        <div class="card-body">
                            @if (Auth::user()->type == 'manager')
                            <h5 class="card-title mb-0">{{ __('There is no active Workspace. Please create Workspace from right side menu.') }}</h5>
                            @else
                            <h5 class="card-title mb-0">{{ __('There is no active Workspace.your manager will invite you to the project.') }}</h5>

                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>

@endsection


@push('scripts')
    <script src="{{ asset('assets/js/apexcharts.min.js') }}"></script>

    @if(Auth::user()->type=='admin')

        <script>

            var taskAreaOptions = {
                series: [
                    {
                        name: '{{ __("Order") }}',
                        data: {!! json_encode($chartData['data']) !!}
                    },
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
                colors: ['#37b37e'],
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
                        text: '{{ __("Orders") }}'
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
                var taskAreaChart = new ApexCharts(document.querySelector("#task-area-chart"), taskAreaOptions);
                taskAreaChart.render();
            }, 100);

        </script>

    @elseif(isset($currentWorkspace) && $currentWorkspace)
        <script>

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
                var taskAreaChart = new ApexCharts(document.querySelector("#task-area-chart"), taskAreaOptions);
                taskAreaChart.render();
            }, 100);

            var projectStatusOptions = {
                series: {!! json_encode($arrProcessPer) !!},

                chart: {
                    height: '400px',
                    width: '500px',
                    type: 'pie',
                },
                colors: ["#00B8D9", "#36B37E", "#2359ee"],
                labels: {!! json_encode($arrProcessLabel) !!},

                plotOptions: {
                    pie: {
                        dataLabels: {
                            offset: -5
                        }
                    }
                },
                title: {
                    text: ""
                },
                dataLabels: {},
                legend: {
                    display: false
                },

            };
            var projectStatusChart = new ApexCharts(document.querySelector("#project-status-chart"), projectStatusOptions);
            projectStatusChart.render();

        </script>
    @endif
@endpush

