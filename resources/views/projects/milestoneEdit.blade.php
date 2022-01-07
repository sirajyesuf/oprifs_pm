@if($milestone && $currentWorkspace)
    <form class="px-3" method="post" action="@auth('web'){{ route('projects.milestone.update',[$currentWorkspace->slug,$milestone->id]) }}@elseauth{{ route('client.projects.milestone.update',[$currentWorkspace->slug,$milestone->id]) }}@endauth">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="milestone-title" class="form-control-label">{{ __('Milestone Title')}}</label>
                    <input type="text" class="form-control form-control-light" id="milestone-title" placeholder="{{ __('Enter Title')}}" value="{{$milestone->title}}" name="title" required>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="milestone-status" class="form-control-label">{{ __('Status')}}</label>
                    <select class="form-control select2" name="status" id="milestone-status" required>
                        <option value="incomplete" @if($milestone->status=='incomplete') selected @endif>{{ __('Incomplete')}}</option>
                        <option value="complete" @if($milestone->status=='complete') selected @endif>{{ __('Complete')}}</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- <div class="form-group">
            <label for="milestone-title" class="form-control-label">{{ __('Milestone Cost')}}</label>
            <div class="form-icon-user">
                <span class="currency-icon">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                <input type="number" class="form-control form-control-light" id="milestone-title" placeholder="{{ __('Enter Cost')}}" value="{{$milestone->cost}}" min="0" name="cost" required>
            </div>
        </div> --}}
        <div class="form-group">
            <label for="task-summary" class="form-control-label">{{ __('Summary')}}</label>
            <textarea class="form-control form-control-light" id="task-summary" rows="3" name="summary">{{$milestone->summary}}</textarea>
        </div>

        <div class="form-group mb-0">
            <input type="submit" class="btn-create badge-blue" value="{{ __('Save') }}">
            <input type="button" class="btn-create bg-gray" data-dismiss="modal" value="{{ __('Cancel') }}">
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
