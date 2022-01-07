@extends('layouts.admin')

@section('page-title') {{__('Bug Report')}} @endsection

@php
    $permissions = Auth::user()->getPermission($project->id);
    $client_keyword = Auth::user()->getGuard() == 'client' ? 'client.' : '';
@endphp

@section('multiple-action-button')
    @if((isset($permissions) && in_array('create bug report',$permissions)) || ($currentWorkspace && $currentWorkspace->permission == 'Owner'))
        <a href="#" class="btn btn-xs btn-white btn-icon-only width-auto" data-ajax-popup="true" data-size="lg"
           data-title="{{ __('Create New Bug') }}" data-url="{{route('projects.bug.report.create',[$currentWorkspace->slug,$project->id])}}"><i class="fas fa-plus mr-1"></i>{{ __('Add New')}}</a>    @endif
    <a href="{{route($client_keyword.'projects.show',[$currentWorkspace->slug,$project->id])}}" class="btn-submit">
        <i class="fa fa-reply"></i> {{ __('Back') }}
    </a>
@endsection

@section('content')

    <section class="section">

        @if($project && $currentWorkspace)
            <div class="row">
                <div class="col-12">
                    <div class="container-kanban">
                        <div class="kanban-board project-task-kanban-box" data-toggle="dragula" data-containers='{{json_encode($statusClass)}}'>
                            @foreach($stages as $stage)
                                <div class="kanban-col px-0">
                                    <div class="card-list card-list-flush">
                                        <div class="card-list-title row align-items-center mb-3">
                                            <div class="col">
                                                <h6 class="mb-0 text-white text-sm">{{$stage->name}}</h6>
                                            </div>
                                            <div class="col text-right">
                                                <span class="badge badge-secondary rounded-pill count">{{$stage->bugs->count()}}</span>
                                            </div>
                                        </div>
                                        <div id="{{'task-list-'.str_replace(' ','_',$stage->id)}}" data-status="{{$stage->id}}" class="card-list-body scrollbar-inner">
                                            @foreach($stage->bugs as $bug)
                                                <div class="card card-progress draggable-item border shadow-none mb-3" id="{{$bug->id}}">
                                                    <div class="card-body">
                                                        <div class="row align-items-center">
                                                            <div class="col-6">
                                                                @if($bug->priority =='Low')
                                                                    <div class="badge badge-pill badge-xs badge-success"> {{ $bug->priority }}</div>
                                                                @elseif($bug->priority =='Medium')
                                                                    <div class="badge badge-pill badge-xs badge-warning"> {{ $bug->priority }}</div>
                                                                @elseif($bug->priority =='High')
                                                                    <div class="badge badge-pill badge-xs badge-danger"> {{ $bug->priority }}</div>
                                                                @endif
                                                            </div>
                                                            <div class="col-6 text-right">
                                                                <div class="actions">
                                                                    @if($currentWorkspace->permission == 'Owner' || isset($permissions))
                                                                        <div class="dropdown float-right">
                                                                            <a href="#" class="action-item" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                <i class="fas fa-ellipsis-h"></i>
                                                                            </a>
                                                                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(22px, 31px, 0px);">
                                                                                    <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="lg" data-title="{{ __('View Bug') }}" data-url="{{route($client_keyword.'projects.bug.report.show',[$currentWorkspace->slug,$bug->project_id,$bug->id])}}">
                                                                                        {{__('View')}}</a>
                                                                                @if($currentWorkspace->permission == 'Owner')
                                                                                    <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="lg" data-title="{{ __('Edit Bug') }}" data-url="{{route('projects.bug.report.edit',[$currentWorkspace->slug,$bug->project_id,$bug->id])}}">
                                                                                        {{__('Edit')}}</a>
                                                                                    <a href="#" class="dropdown-item" data-confirm="{{ __('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="document.getElementById('delete-form-{{$bug->id}}').submit();">
                                                                                        {{__('Delete')}}
                                                                                    </a>
                                                                                    <form id="delete-form-{{$bug->id}}" action="{{ route('projects.bug.report.destroy',[$currentWorkspace->slug,$bug->project_id,$bug->id]) }}" method="POST" style="display: none;">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                    </form>
                                                                                @elseif(isset($permissions))
                                                                                    @if(in_array('edit bug report',$permissions))
                                                                                        <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="lg" data-title="{{ __('Edit Bug') }}" data-url="{{route($client_keyword.'projects.bug.report.edit',[$currentWorkspace->slug,$bug->project_id,$bug->id])}}">
                                                                                            {{__('Edit')}}
                                                                                        </a>
                                                                                    @endif
                                                                                    @if(in_array('delete bug report',$permissions))
                                                                                        <a href="#" class="dropdown-item" data-confirm="{{ __('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="document.getElementById('delete-form-{{$bug->id}}').submit();">
                                                                                            {{__('Delete')}}
                                                                                        </a>
                                                                                        <form id="delete-form-{{$bug->id}}" action="{{ route($client_keyword.'projects.bug.report.destroy',[$currentWorkspace->slug,$bug->project_id,$bug->id]) }}" method="POST" style="display: none;">
                                                                                            @csrf
                                                                                            @method('DELETE')
                                                                                        </form>
                                                                                    @endif
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <a href="#" data-url="{{route($client_keyword.'projects.bug.report.show',[$currentWorkspace->slug,$bug->project_id,$bug->id])}}" data-ajax-popup="true" data-title="{{__('Bug Detail')}}" class="h6 task-title">{{$bug->title}}</a>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="action-item">
                                                                    {{\App\Models\Utility::dateFormat($bug->start_date)}}
                                                                </div>
                                                            </div>
                                                            <div class="col text-right">
                                                                <div class="action-item">
                                                                    {{\App\Models\Utility::dateFormat($bug->due_date)}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="action-item">
                                                                    {{$bug->comments->count()}} {{ __('Comments') }}
                                                                </div>
                                                            </div>
                                                            <div class="col text-right">
                                                                @if($currentWorkspace->permission == 'Owner' || isset($permissions))
                                                                    <div class="avatar-group">
                                                                        <a href="#" class="avatar rounded-circle avatar-sm">
                                                                            <img alt="image" data-toggle="tooltip" data-original-title="{{$bug->user->name}}" @if($bug->user->avatar) src="{{asset('/storage/avatars/'.$bug->user->avatar)}}" @else avatar="{{ $bug->user->name }}"@endif>
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <span class="empty-container" data-placeholder="Empty"></span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div> <!-- end col -->
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
    @push('scripts')
        <!-- third party js -->
        <script src="{{ asset('assets/js/dragula.min.js') }}"></script>
        <script>
            !function (a) {
                "use strict";
                var t = function () {
                    this.$body = a("body")
                };
                t.prototype.init = function () {
                    a('[data-toggle="dragula"]').each(function () {
                        var t = a(this).data("containers"), n = [];
                        if (t) for (var i = 0; i < t.length; i++) n.push(a("#" + t[i])[0]); else n = [a(this)[0]];
                        var r = a(this).data("handleclass");
                        r ? dragula(n, {
                            moves: function (a, t, n) {
                                return n.classList.contains(r)
                            }
                        }) : dragula(n).on('drop', function (el, target, source, sibling) {
                            var sort = [];
                            $("#" + target.id + " > div").each(function (key) {
                                sort[key] = $(this).attr('id');
                            });
                            var id = el.id;
                            var old_status = $("#" + source.id).data('status');
                            var new_status = $("#" + target.id).data('status');
                            var project_id = '{{$project->id}}';

                            $("#" + source.id).parents('.card-list').find('.count').text($("#" + source.id + " > div").length);
                            $("#" + target.id).parents('.card-list').find('.count').text($("#" + target.id + " > div").length);
                            $.ajax({
                                url:'{{route($client_keyword.'projects.bug.report.update.order',[$currentWorkspace->slug,$project->id])}}',
                                type: 'POST',
                                data: {
                                    id: id,
                                    sort: sort,
                                    new_status: new_status,
                                    old_status: old_status,
                                    project_id: project_id,
                                },
                                success: function (data) {
                                    // console.log(data);
                                }
                            });
                        });
                    })
                }, a.Dragula = new t, a.Dragula.Constructor = t
            }(window.jQuery), function (a) {
                "use strict";
                @if(isset($permissions) && in_array('move bug report',$permissions) || $currentWorkspace->permission == 'Owner')
                    a.Dragula.init()
                @endif
            }(window.jQuery);
        </script>
        <!-- third party js ends -->
        <script>
            $(document).on('click', '#form-comment button', function (e) {
                var comment = $.trim($("#form-comment textarea[name='comment']").val());
                if (comment != '') {
                    $.ajax({
                        url: $("#form-comment").data('action'),
                        data: {comment: comment},
                        type: 'POST',
                        success: function (data) {
                            data = JSON.parse(data);

                            if (data.user_type == 'Client') {
                                var avatar = "avatar='" + data.client.name + "'";
                                var html = "<li class='media border-bottom mb-3'>" +
                                    "                    <img class='mr-3 avatar-sm rounded-circle img-thumbnail' width='60' " + avatar + " alt='" + data.client.name + "'>" +
                                    "                    <div class='media-body mb-2'>" +
                                    "                    <div class='float-left'>" +
                                    "                        <h5 class='mt-0 mb-1 form-control-label'>" + data.client.name + "</h5>" +
                                    "                        " + data.comment +
                                    "                    </div>" +
                                    "                    </div>" +
                                    "                </li>";
                            } else {
                                var avatar = (data.user.avatar) ? "src='{{asset('/storage/avatars/')}}/" + data.user.avatar + "'" : "avatar='" + data.user.name + "'";
                                var html = "<li class='media border-bottom mb-3'>" +
                                    "                    <img class='mr-3 avatar-sm rounded-circle img-thumbnail' width='60' " + avatar + " alt='" + data.user.name + "'>" +
                                    "                    <div class='media-body mb-2'>" +
                                    "                    <div class='float-left'>" +
                                    "                        <h5 class='mt-0 mb-1 form-control-label'>" + data.user.name + "</h5>" +
                                    "                        " + data.comment +
                                    "                           </div>" +
                                    "                           <div class='float-right'>" +
                                    "                               <a href='#' class='delete-icon delete-comment' data-url='" + data.deleteUrl + "'>" +
                                    "                                   <i class='fas fa-trash'></i>" +
                                    "                               </a>" +
                                    "                           </div>" +
                                    "                    </div>" +
                                    "                </li>";
                            }
                            $("#comments").prepend(html);
                            LetterAvatar.transform();
                            $("#form-comment textarea[name='comment']").val('');
                            show_toastr('{{__('Success')}}', '{{ __("Comment Added Successfully!")}}', 'success');
                        },
                        error: function (data) {
                            show_toastr('{{__('Error')}}', '{{ __("Some Thing Is Wrong!")}}', 'error');
                        }
                    });
                } else {
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
                        // console.log(data);
                        var delLink = '';

                        if (data.deleteUrl.length > 0) {
                            delLink = "<a href='#' class='delete-icon delete-comment-file'  data-url='" + data.deleteUrl + "'>" +
                                "                                        <i class='fas fa-trash'></i>" +
                                "                                    </a>";
                        }

                        var html = "<div class='card mb-1 shadow-none border'>" +
                            "                        <div class='card-body py-2'>" +
                            "                            <div class='row align-items-center'>" +
                            "                                <div class='col-auto'>" +
                            "                                    <div class='avatar-sm'>" +
                            "                                        <span class='avatar-title rounded text-uppercase'>" +
                            data.extension +
                            "                                        </span>" +
                            "                                    </div>" +
                            "                                </div>" +
                            "                                <div class='col pl-0'>" +
                            "                                    <a href='#' class='text-muted form-control-label'>" + data.name + "</a>" +
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
                            show_toastr('{{__('Error')}}', data.message, 'error');
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
        </script>
    @endpush
@endif
