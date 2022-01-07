@if($currentWorkspace && $task)

    <div class="p-2">
        <div class="form-control-label">{{ __('Description')}}:</div>

        <p class="text-muted mb-4">
            {{ $task->description }}
        </p>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="form-control-label">{{ __('Create Date')}}</div>
                <p class="mt-1">{{ \App\Models\Utility::dateFormat($task->created_at) }}</p>
            </div>
            <div class="col-md-3">
                <div class="form-control-label">{{ __('Due Date')}}</div>
                <p class="mt-1">{{ \App\Models\Utility::dateFormat($task->due_date) }}</p>
            </div>
            <div class="col-md-3">
                <div class="form-control-label">{{ __('Assigned')}}</div>
                @if($users = $task->users())
                    @foreach($users as $user)
                        <img @if($user->avatar) src="{{asset('/storage/avatars/'.$user->avatar)}}" @else avatar="{{ $user->name }}" @endif class="rounded-circle mt-1 w-20">
                    @endforeach
                @endif
            </div>
            <div class="col-md-3">
                <div class="form-control-label">{{ __('Milestone')}}</div>
                @php($milestone = $task->milestone())
                <p class="mt-1">@if($milestone) {{$milestone->title}} @endif</p>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
        <li>
            <a class=" active" id="comments-tab" data-toggle="tab" href="#comments-data" role="tab" aria-controls="home" aria-selected="false"> {{ __('Comments') }} </a>
        </li>
        <li class="annual-billing">
            <a id="file-tab" data-toggle="tab" href="#file-data" role="tab" aria-controls="profile" aria-selected="false"> {{ __('Files') }} </a>
        </li>
        {{-- <li class="annual-billing">
            <a id="sub-task-tab" data-toggle="tab" href="#sub-task-data" role="tab" aria-controls="contact" aria-selected="true"> {{ __('Sub Task') }} </a>
        </li> --}}
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade active show" id="comments-data" role="tabpanel" aria-labelledby="home-tab">
            <form method="post" id="form-comment" data-action="{{route('comment.store',[$currentWorkspace->slug,$task->project_id,$task->id,$clientID])}}">
                <textarea class="form-control form-control-light mb-2" name="comment" placeholder="{{ __('Write message')}}" id="example-textarea" rows="3" required></textarea>
                <div class="text-right">
                    <div class="btn-group mb-2 ml-2 d-sm-inline-block">
                        <button type="button" class="btn-create badge-blue">{{ __('Submit')}}</button>
                    </div>
                </div>
            </form>
            <ul class="list-unstyled list-unstyled-border mt-3" id="task-comments">
                @foreach($task->comments as $comment)
                    <li class="media border-bottom mb-3">
                        <img class="mr-3 avatar-sm rounded-circle img-thumbnail" width="60"
                             @if($comment->user_type != 'Client') @if($comment->user->avatar) src="{{asset('/storage/avatars/'.$comment->user->avatar)}}" @else avatar="{{ $comment->user->name }}" @endif alt="{{ $comment->user->name }}"
                             @else avatar="{{ $comment->client->name }}" alt="{{ $comment->client->name }}" @endif />
                        <div class="media-body mb-2">
                            <div class="float-left">
                                <h5 class="mt-0 mb-1 form-control-label">@if($comment->user_type!='Client'){{$comment->user->name}}@else {{$comment->client->name}} @endif</h5>
                                {{$comment->comment}}
                            </div>
                            @auth('web')
                                <div class="float-right">
                                    <a href="#" class="delete-icon delete-comment" data-url="{{route('comment.destroy',[$currentWorkspace->slug,$task->project_id,$task->id,$comment->id])}}">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="tab-pane fade" id="file-data" role="tabpanel" aria-labelledby="profile-tab">
            <div class="form-group m-0">
                <form method="post" id="form-file" enctype="multipart/form-data" data-url="{{ route('comment.store.file',[$currentWorkspace->slug,$task->project_id,$task->id,$clientID]) }}">
                    <div class="choose-file form-group">
                        <label for="file" class="form-control-label">
                            <div>{{__('Choose file here')}}</div>
                            <input type="file" class="form-control" name="file" id="file" data-filename="file_create">
                            <span class="invalid-feedback" id="file-error" role="alert">
                                <strong></strong>
                            </span>
                        </label>
                        <p class="file_create"></p>
                    </div>
                    <div class="text-right">
                        <div class="btn-group mb-2 ml-2 d-none d-sm-inline-block">
                            <button type="submit" class="btn-create badge-blue">{{ __('Upload') }}</button>
                        </div>
                    </div>
                </form>
            </div>

            <div id="comments-file" class="mt-3">
                @foreach($task->taskFiles as $file)
                    <div class="card pb-0 mb-1 shadow-none border">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="avatar-sm">
                                        <span class="avatar-title rounded text-uppercase">
                                            {{$file->extension}}
                                        </span>
                                    </div>
                                </div>
                                <div class="col pl-0">
                                    <a href="#" class="text-muted form-control-label">{{$file->name}}</a>
                                    <p class="mb-0">{{$file->file_size}}</p>
                                </div>
                                <div class="col-auto">
                                    <!-- Button -->
                                    <a download href="{{asset('/storage/tasks/'.$file->file)}}" class="edit-icon">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    @auth('web')
                                        <a href="#" class="delete-icon delete-comment-file" data-url="{{route('comment.destroy.file',[$currentWorkspace->slug,$task->project_id,$task->id,$file->id])}}">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="tab-pane fade mt-3" id="sub-task-data" role="tabpanel" aria-labelledby="contact-tab">

            <div class="text-right mb-3">
                <a href="#" class="btn-create badge-blue" data-toggle="collapse" data-target="#form-subtask">{{ __('Add Sub Task')}}</a>
            </div>
            <form method="post" id="form-subtask" class="collapse" data-action="{{route('subtask.store',[$currentWorkspace->slug,$task->project_id,$task->id,$clientID])}}">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label">{{__('Name')}}</label>
                                <input type="text" name="name" class="form-control" required placeholder="{{__('Sub Task Name')}}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label">{{__('Due Date')}}</label>
                                <input class="form-control datepicker" type="text" id="due_date" name="due_date" autocomplete="off" required="required">
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="btn-group mb-2 ml-2 d-sm-inline-block">
                            <button type="submit" class="btn-create badge-blue create-subtask">{{ __('Create')}}</button>
                        </div>
                    </div>
                </div>
            </form>
            <ul class="list-group mt-3" id="subtasks">
                @foreach($task->sub_tasks as $subTask)
                    <li class="list-group-item py-3">
                        <div class="custom-control custom-switch float-left">
                            <input type="checkbox" class="custom-control-input" name="option" id="option{{ $subTask->id }}" @if($subTask->status) checked @endif data-url="{{route('subtask.update',[$currentWorkspace->slug,$task->project_id,$subTask->id])}}">
                            <label class="custom-control-label form-control-label" for="option{{ $subTask->id }}">{{$subTask->name}}</label>
                        </div>
                        <div class="float-right">
                            <a href="#" class="delete-icon delete-subtask" data-url="{{route('subtask.destroy',[$currentWorkspace->slug,$task->project_id,$subTask->id])}}">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
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
