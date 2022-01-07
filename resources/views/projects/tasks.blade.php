@extends('layouts.admin')

@section('page-title') {{__('Tasks')}} @endsection

@push('css-page')
    <style>
        .page-content .select2-container {
            z-index: 0 !important;
        }
    </style>
@endpush
@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body pt-3 pl-3">
                <div class="row">
                    <div class="col-md-2">
                        <select class="select2" size="sm" name="project" id="project">
                            <option value="">{{__('All Projects')}}</option>
                            @foreach($projects as $project)
                                <option value="{{$project->id}}">{{$project->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($currentWorkspace->permission == 'Owner')
                        <div class="col-md-2">
                            <select class="select2" size="sm" name="all_users" id="all_users">
                                <option value="">{{__('All Users')}}</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-md-2">
                        <select class="select2" size="sm" name="status" id="status">
                            <option value="">{{__('All Status')}}</option>
                            @foreach($stages as $stage)
                                <option value="{{$stage->id}}">{{__($stage->name)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <select class="select2" size="sm" name="priority" id="priority">
                            <option value="">{{__('All Priority')}}</option>
                            <option value="Low">{{ __('Low')}}</option>
                            <option value="Medium">{{ __('Medium')}}</option>
                            <option value="High">{{ __('High')}}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="month-btn form-control-light" id="duration1" name="duration" value="{{__('Select Date Range')}}">
                        <input type="hidden" name="start_date1" id="start_date1">
                        <input type="hidden" name="due_date1" id="end_date1">
                    </div>
                    <div class="col-md-2">
                        <select class="select2" size="sm" name="due_date_order" id="due_date_order">
                            {{--<option value="">{{__('By Due Date')}}</option>
                            <option value="expired">{{ __('Expired')}}</option>
                            <option value="today">{{ __('Today')}}</option>
                            <option value="in_7_days">{{ __('In 7 days')}}</option>--}}
                            <option value="due_date,asc">{{ __('Oldest')}}</option>
                            <option value="due_date,desc">{{ __('Newest')}}</option>
                        </select>
                    </div>
                    <button class="btn-create badge-blue btn-filter">{{ __('Apply')}}</button>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover mb-0 animated" id="tasks-selection-datatable">
                                <thead>
                                <th>{{__('Task')}}</th>
                                <th>{{__('Project')}}</th>
                                <th>{{__('Milestone')}}</th>
                                <th>{{__('Due Date')}}</th>
                                @if($currentWorkspace->permission == 'Owner' || Auth::user()->getGuard() == 'client')
                                    <th>{{__('Assigned to')}}</th>
                                @endif
                                <th>{{__('Status')}}</th>
                                <th>{{__('Priority')}}</th>
                                @if($currentWorkspace->permission == 'Owner')
                                    <th>{{__('Action')}}</th>
                                @endif
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('css-page')
@endpush

@push('scripts')
    <script>
        $(function () {
            // var start = moment().startOf('hour').add(-15,'day');
            // var end = moment().add(45,'day');
            function cb(start, end) {
                $("#duration1").val(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
                $('input[name="start_date1"]').val(start.format('YYYY-MM-DD'));
                $('input[name="due_date1"]').val(end.format('YYYY-MM-DD'));
            }

            $('#duration1').daterangepicker({
                // timePicker: true,
                autoApply: true,
                autoclose: true,
                autoUpdateInput: false,
                // startDate: start,
                // endDate: end,
                locale: {
                    format: 'MMM D, YY hh:mm A',
                    applyLabel: "{{__('Apply')}}",
                    cancelLabel: "{{__('Cancel')}}",
                    fromLabel: "{{__('From')}}",
                    toLabel: "{{__('To')}}",
                    daysOfWeek: [
                        "{{__('Sun')}}",
                        "{{__('Mon')}}",
                        "{{__('Tue')}}",
                        "{{__('Wed')}}",
                        "{{__('Thu')}}",
                        "{{__('Fri')}}",
                        "{{__('Sat')}}"
                    ],
                    monthNames: [
                        "{{__('January')}}",
                        "{{__('February')}}",
                        "{{__('March')}}",
                        "{{__('April')}}",
                        "{{__('May')}}",
                        "{{__('June')}}",
                        "{{__('July')}}",
                        "{{__('August')}}",
                        "{{__('September')}}",
                        "{{__('October')}}",
                        "{{__('November')}}",
                        "{{__('December')}}"
                    ],
                }
            }, cb);
            // cb(start,end);
        });

        $(document).ready(function () {
            var table = $("#tasks-selection-datatable").DataTable({
                order: [],
                select: {style: "multi"},
                "language": dataTableLang,
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            });

            $(document).on("click", ".btn-filter", function () {
                getData();
            });

            function getData() {
                table.clear().draw();
                $("#tasks-selection-datatable tbody tr").html('<td colspan="11" class="text-center"> {{ __("Loading ...") }}</td>');

                var data = {
                    project: $("#project").val(),
                    assign_to: $("#all_users").val(),
                    priority: $("#priority").val(),
                    due_date_order: $("#due_date_order").val(),
                    status: $("#status").val(),
                    start_date: $("#start_date1").val(),
                    end_date: $("#end_date1").val(),
                };

                $.ajax({
                    url: '{{route('tasks.ajax',[$currentWorkspace->slug])}}',
                    type: 'POST',
                    data: data,
                    success: function (data) {
                        table.rows.add(data.data).draw();
                        loadConfirm();
                    },
                    error: function (data) {
                        show_toastr('Info', data.error, 'info')
                    }
                })
            }

            getData();

        });
    </script>
@endpush
