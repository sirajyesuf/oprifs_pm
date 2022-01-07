@extends('layouts.admin')

@section('page-title') {{__('Calendar')}} @endsection

@section('multiple-action-button')
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6 pt-lg-3 pt-xl-2">
        <select class="select2" id="projects" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
            <option value="@auth('web'){{route('calender.index',$currentWorkspace->slug)}}@else{{route('client.calender.index',$currentWorkspace->slug)}}@endif">{{__('All Projects')}}</option>
            @foreach($projects as $project)
                <option value="@auth('web'){{route('calender.index',$currentWorkspace->slug)}}@else{{route('client.calender.index',$currentWorkspace->slug)}}@endif{{ '/'.$project->id }}" @if($project_id == $project->id) selected @endif>{{ $project->name }}</option>
            @endforeach
        </select>
    </div>
@endsection

@section('content')
    <div class="card author-box card-primary">
        <div class="card-header">
            <div class="row justify-content-between align-items-center full-calender">
                <div class="col d-flex align-items-center">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="#" class="fullcalendar-btn-prev btn btn-sm btn-neutral">
                            <i class="fas fa-angle-left"></i>
                        </a>
                        <a href="#" class="fullcalendar-btn-next btn btn-sm btn-neutral">
                            <i class="fas fa-angle-right"></i>
                        </a>
                    </div>
                    <h5 class="fullcalendar-title h4 d-inline-block font-weight-400 mb-0"></h5>
                </div>
                <div class="col-lg-6 mt-3 mt-lg-0 text-lg-right">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="month">{{__('Month')}}</a>
                        <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="basicWeek">{{__('Week')}}</a>
                        <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="basicDay">{{__('Day')}}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id='calendar-container'>
                <div id='calendar' data-toggle="task-calendar"></div>
            </div>
        </div>
    </div>
@endsection

@if($currentWorkspace)
    @push('css-page')
        <link rel="stylesheet" href="{{ asset('assets/css/fullcalendar.min.css') }}">
    @endpush
    @push('scripts')
        <script src="{{ asset('assets/js/fullcalendar.min.js') }}"></script>
        <script>

            var e, t, a = $('[data-toggle="task-calendar"]');
            a.length && (
                t = {
                header: {right: "", center: "", left: ""},
                buttonIcons: {prev: "calendar--prev", next: "calendar--next"},
                theme: !1,
                @auth('web')
                selectable: !0,
                selectHelper: !0,
                editable: !0,
                @endauth
                events: {!! json_encode($arrayJson) !!},
                eventStartEditable: !1,
                locale: '{{basename(App::getLocale())}}',
                dayClick: function (e) {
                    var t = moment(e).toISOString();
                },
                eventResize: function (event) {
                    var eventObj = {
                        start: event.start.format(),
                        end: event.end.format(),
                    };

                    $.ajax({
                        url: event.resize_url,
                        method: 'POST',
                        data: eventObj,
                        success: function (response) {
                        },
                        error: function (data) {
                            data = data.responseJSON;
                        }
                    });
                },
                viewRender: function (t) {
                    e.fullCalendar("getDate").month(), $(".fullcalendar-title").html(t.title)
                },
                eventClick: function (e, t) {

                        @auth('web')
                    var title = e.title;
                    var url = e.url;

                    if (typeof url != 'undefined') {
                        $("#commonModal .modal-title").html(title);
                        $("#commonModal .modal-dialog").addClass('modal-md');
                        $("#commonModal").modal('show');
                        $.get(url, {}, function (data) {
                            $('#commonModal .modal-body .card-box').html(data);
                            $('.datepicker').daterangepicker({
                                singleDatePicker: true,
                                format: 'yyyy-mm-dd',
                                locale: date_picker_locale,
                            });
                        });
                        return false;
                    }
                    @endauth
                }
            },
             (e = a).fullCalendar(t),
                $("body").on("click", "[data-calendar-view]", function (t) {
                    t.preventDefault(), $("[data-calendar-view]").removeClass("active"), $(this).addClass("active");
                    var a = $(this).attr("data-calendar-view");
                    e.fullCalendar("changeView", a)
                }),
                 $("body").on("click", ".fullcalendar-btn-next", function (t) {
                t.preventDefault(), e.fullCalendar("next")
            }), $("body").on("click", ".fullcalendar-btn-prev", function (t) {
                t.preventDefault(), e.fullCalendar("prev")
            }));
            @auth('web')
            $(document).on("click", '.fc-day-grid-event', function (e) {
                e.preventDefault();
                var title = $(this).find('.fc-content .fc-title').text();
                var size = 'lg';
                var url = $(this).attr('href');
                if (typeof url != 'undefined') {
                    $("#commonModal .modal-title").html(title);
                    $("#commonModal .modal-dialog").addClass('modal-' + size);
                    $("#commonModal").modal('show');

                    $.get(url, {}, function (data) {
                        $('#commonModal .modal-body .card-box').html(data);
                        $('.datepicker').daterangepicker({
                            singleDatePicker: true,
                            format: 'yyyy-mm-dd',
                            locale: date_picker_locale,
                        });
                    });
                }
            });

            $(document).on('click', '#form-comment button', function (e) {
                var comment = $("#form-comment textarea[name='comment']");
                if ($.trim(comment.val()) != '') {
                    $.ajax({
                        url: $("#form-comment").data('action'),
                        data: {comment: comment.val()},
                        type: 'POST',
                        success: function (data) {
                            data = JSON.parse(data);

                            if (data.user_type == 'Client') {
                                var avatar = "avatar='" + data.client.name + "'";
                                var html = "<li class='media border-bottom mb-3'>" +
                                    "                    <img class='mr-3 avatar-sm rounded-circle img-thumbnail' width='60' " + avatar + " alt='" + data.client.name + "'>" +
                                    "                    <div class='media-body mb-2'>" +
                                    "                        <h5 class='mt-0 mb-1 form-control-label'>" + data.client.name + "</h5>" +
                                    "                        " + data.comment +
                                    "                    </div>" +
                                    "                </li>";
                            } else {
                                var avatar = (data.user.avatar) ? "src='{{asset('/storage/avatars/')}}/" + data.user.avatar + "'" : "avatar='" + data.user.name + "'";
                                var html = "<li class='media border-bottom mb-3'>" +
                                    "                    <img class='mr-3 avatar-sm rounded-circle img-thumbnail' width='60' " + avatar + " alt='" + data.user.name + "'>" +
                                    "                    <div class='media-body mb-2'>" +
                                    "                           <div class='float-left'>" +
                                    "                               <h5 class='mt-0 mb-1 form-control-label'>" + data.user.name + "</h5>" +
                                    "                               " + data.comment +
                                    "                           </div>" +
                                    "                           <div class='float-right'>" +
                                    "                               <a href='#' class='delete-icon' data-url='" + data.deleteUrl + "'>" +
                                    "                                   <i class='fas fa-trash'></i>" +
                                    "                               </a>" +
                                    "                           </div>" +
                                    "                    </div>" +
                                    "                </li>";
                            }

                            $("#comments").prepend(html);
                            $("#form-comment textarea[name='comment']").val('');
                            show_toastr('{{__('Success')}}', '{{ __("Comment Added Successfully!")}}', 'success');
                        },
                        error: function (data) {
                            show_toastr('{{__('Error')}}', '{{ __("Some Thing Is Wrong!")}}', 'error');
                        }
                    });
                } else {
                    comment.focus();
                    show_toastr('{{__('Error')}}', '{{ __("Please write comment!")}}', 'error');
                }
            });
            $(document).on("click", ".delete-comment", function () {
                if (confirm('{{__('Are you sure ?')}}')) {
                    var btn = $(this);
                    $.ajax({
                        url: $(this).attr('data-url'),
                        type: 'DELETE',
                        dataType: 'JSON',
                        success: function (data) {
                            show_toastr('{{__('Success')}}', '{{ __("Comment Deleted Successfully!")}}', 'success');
                            btn.closest('.media').remove();
                        },
                        error: function (data) {
                            data = data.responseJSON;
                            if (data.message) {
                                show_toastr('{{__('Error')}}', data.message, 'error');
                            } else {
                                show_toastr('{{__('Error')}}', '{{ __("Some Thing Is Wrong!")}}', 'error');
                            }
                        }
                    });
                }
            });
            $(document).on('click', '#form-subtask .create-subtask', function (e) {
                e.preventDefault();

                var name = $('#form-subtask input[name="name"]');
                var due_date = $('#form-subtask input[name="due_date"]');

                if ($.trim(name.val()) != '' && due_date.val() != '') {
                    $.ajax({
                        url: $("#form-subtask").data('action'),
                        type: 'POST',
                        data: {
                            name: name.val(),
                            due_date: due_date.val()
                        },
                        dataType: 'json',
                        success: function (data) {

                            show_toastr('{{__('Success')}}', '{{ __("Sub Task Added Successfully!")}}', 'success');

                            var html = '<li class="list-group-item py-3">' +
                                '                                <label class="custom-control custom-switch float-left">' +
                                '                                    <input type="checkbox" class="custom-control-input" name="option" id="option" value="' + data.id + '" data-url="' + data.updateUrl + '">' +
                                '                                    <label class="custom-control-label form-control-label" for="option">' + data.name + '</label>' +
                                '                                </label>' +
                                '                                <div class="float-right">' +
                                '                                    <a href="#" class="delete-icon delete-subtask" data-url="' + data.deleteUrl + '">' +
                                '                                        <i class="fas fa-trash"></i>' +
                                '                                    </a>' +
                                '                                </div>' +
                                '                            </li>';
                            $("#subtasks").prepend(html);
                            name.val('');
                            due_date.val('');
                            $("#form-subtask").collapse('toggle');
                        },
                        error: function (data) {
                            data = data.responseJSON;
                            if (data.message) {
                                show_toastr('{{__('Error')}}', data.message, 'error');
                                $('#file-error').text(data.errors.file[0]).show();
                            } else {
                                show_toastr('{{__('Error')}}', '{{ __("Some Thing Is Wrong!")}}', 'error');
                            }
                        }
                    });
                } else {
                    name.focus();
                    show_toastr('{{__('Error')}}', '{{ __("Some Thing Is Wrong!")}}', 'error');
                }
            });
            $(document).on("change", "#subtasks input[type=checkbox]", function () {
                $.ajax({
                    url: $(this).attr('data-url'),
                    type: 'POST',
                    dataType: 'JSON',
                    success: function (data) {
                        show_toastr('{{__('Success')}}', '{{ __("Subtask Updated Successfully!")}}', 'success');
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        if (data.message) {
                            show_toastr('{{__('Error')}}', data.message, 'error');
                        } else {
                            show_toastr('{{__('Error')}}', '{{ __("Some Thing Is Wrong!")}}', 'error');
                        }
                    }
                });
            });
            $(document).on("click", ".delete-subtask", function () {
                if (confirm('{{__('Are you sure ?')}}')) {
                    var btn = $(this);
                    $.ajax({
                        url: $(this).attr('data-url'),
                        type: 'DELETE',
                        dataType: 'JSON',
                        success: function (data) {
                            show_toastr('{{__('Success')}}', '{{ __("Subtask Deleted Successfully!")}}', 'success');
                            btn.closest('.list-group-item').remove();
                        },
                        error: function (data) {
                            data = data.responseJSON;
                            if (data.message) {
                                show_toastr('{{__('Error')}}', data.message, 'error');
                            } else {
                                show_toastr('{{__('Error')}}', '{{ __("Some Thing Is Wrong!")}}', 'error');
                            }
                        }
                    });
                }
            });
            $(document).on('submit', '#form-file', function (e) {
                e.preventDefault();

                $.ajax({
                    url: $("#form-file").data('url'),
                    type: 'POST',
                    data: new FormData(this),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        show_toastr('{{__('Success')}}', '{{ __("Comment Added Successfully!")}}', 'success');

                        $('#file-error, .file_create').text('').hide();

                        var delLink = '';

                        if (data.deleteUrl.length > 0) {
                            delLink = "<a href='#' class='delete-icon delete-comment-file'  data-url='" + data.deleteUrl + "'>" +
                                "                                        <i class='fas fa-trash'></i>" +
                                "                                    </a>";
                        }

                        var html = "<div class='card pb-0 mb-1 shadow-none border'>" +
                            "                        <div class='card-body p-3'>" +
                            "                            <div class='row align-items-center'>" +
                            "                                <div class='col-auto'>" +
                            "                                    <div class='avatar-sm'>" +
                            "                                        <span class='avatar-title rounded text-uppercase'>" + data.extension + "</span>" +
                            "                                    </div>" +
                            "                                </div>" +
                            "                                <div class='col pl-0'>" +
                            "                                    <a href='#' class='text-muted font-control-label'>" + data.name + "</a>" +
                            "                                    <p class='mb-0'>" + data.file_size + "</p>" +
                            "                                </div>" +
                            "                                <div class='col-auto'>" +
                            "                                    <a download href='{{asset('/storage/tasks/')}}/" + data.file + "' class='edit-icon'>" +
                            "                                        <i class='fas fa-download'></i>" +
                            "                                    </a>" +
                            delLink +
                            "                                </div>" +
                            "                            </div>" +
                            "                        </div>" +
                            "                    </div>";
                        $("#comments-file").prepend(html);
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        if (data.message) {
                            show_toastr('{{__('Error')}}', data.errors.file[0], 'error');
                            $('#file-error').text(data.errors.file[0]).show();
                        } else {
                            show_toastr('{{__('Error')}}', '{{ __("Some Thing Is Wrong!")}}', 'error');
                        }
                    }
                });
            });
            $(document).on("click", ".delete-comment-file", function () {
                if (confirm('{{__('Are you sure ?')}}')) {
                    var btn = $(this);
                    $.ajax({
                        url: $(this).attr('data-url'),
                        type: 'DELETE',
                        dataType: 'JSON',
                        success: function (data) {
                            show_toastr('{{__('Success')}}', '{{ __("File Deleted Successfully!")}}', 'success');
                            btn.closest('.border').remove();
                        },
                        error: function (data) {
                            data = data.responseJSON;
                            if (data.message) {
                                show_toastr('{{__('Error')}}', data.message, 'error');
                            } else {
                                show_toastr('{{__('Error')}}', '{{ __("Some Thing Is Wrong!")}}', 'error');
                            }
                        }
                    });
                }
            });
            @endauth

        </script>
    @endpush
@endif
