<form class="px-3" method="post" action="{{ route('projects.share',[$currentWorkspace->slug,$project->id]) }}">
    @csrf
    <div class="form-group col-md-12 mb-0">
        <label for="users_list">{{ __('Clients') }}</label>
        <select class="select2 form-control select2-multiple" data-toggle="select2" required name="clients[]" multiple="multiple" data-placeholder="{{ __('Select Clients ...') }}">
            @foreach($currentWorkspace->clients as $client)
                @if($client->pivot->is_active)
                    <option value="{{$client->id}}">{{$client->name}} - {{$client->email}}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-12">
        <input type="submit" value="{{ __('Share To Clients') }}" class="btn-create badge-blue">
        <input type="button" value="{{ __('Cancel') }}" class="btn-create bg-gray" data-dismiss="modal">
    </div>
</form>
