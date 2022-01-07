<form method="post" action="{{ route('projects.user.permission.store',[$currentWorkspace->slug,$project->id,$user->id]) }}">
    @csrf
    @include('projects.project_permission')
    <div class="col-md-12">
        <input type="submit" class="btn-create badge-blue" value="{{ __('Save') }}">
        <input type="button" class="btn-create bg-gray" data-dismiss="modal" value="{{ __('Cancel') }}">
    </div>
</form>
