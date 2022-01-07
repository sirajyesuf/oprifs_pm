@if($project && $currentWorkspace && $bug)

    <form class="px-3" method="post" action="@auth('web'){{ route('projects.bug.report.update',[$currentWorkspace->slug,$project->id,$bug->id]) }}@elseauth{{ route('client.projects.bug.report.update',[$currentWorkspace->slug,$project->id,$bug->id]) }}@endauth">
        @csrf
        <div class="row">
            <div class="form-group col-md-8">
                <label class="form-control-label">{{ __('Title')}}</label>
                <input type="text" class="form-control" id="task-title" placeholder="{{ __('Enter Title')}}" name="title" value="{{$bug->title}}" required>
            </div>

            <div class="form-group col-md-4">
                <label for="task-priority" class="form-control-label">{{ __('Priority')}}</label>
                <select class="form-control select2" name="priority" id="task-priority" required>
                    <option value="Low" @if($bug->priority=='Low') selected @endif>{{ __('Low')}}</option>
                    <option value="Medium" @if($bug->priority=='Medium') selected @endif>{{ __('Medium')}}</option>
                    <option value="High" @if($bug->priority=='High') selected @endif>{{ __('High')}}</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="assign_to" class="form-control-label">{{ __('Assign To')}}</label>
                <select class="form-control select2" id="assign_to" name="assign_to" required>
                    @foreach($users as $u)
                        <option @if($bug->assign_to==$u->id) selected @endif value="{{$u->id}}">{{$u->name}} - {{$u->email}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="status" class="form-control-label">{{ __('Status')}}</label>
                <select class="form-control select2" id="status" name="status" required>
                    @foreach($arrStatus as $id => $status)
                        <option @if($bug->status==$id) selected @endif value="{{$id}}">{{__($status)}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-12 mb-0">
                <label for="task-description" class="form-control-label">{{ __('Description')}}</label>
                <textarea class="form-control" id="task-description" rows="3" name="description">{{$bug->description}}</textarea>
            </div>
            <div class="col-md-12">
                <input type="submit" value="{{ __('Save') }}" class="btn-create badge-blue">
                <input type="button" value="{{ __('Cancel') }}" class="btn-create bg-gray" data-dismiss="modal">
            </div>
        </div>
    </form>

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
