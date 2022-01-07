<form class="px-3" method="post" action="{{ route('tax.store',[$currentWorkspace->slug]) }}">
    @csrf
    <div class="form-group">
        <label for="name" class="form-control-label">{{ __('Name') }}</label>
        <input type="text" class="form-control" id="name" name="name" required/>
    </div>
    <div class="form-group">
        <label for="rate" class="form-control-label">{{ __('Rate') }}</label>
        <input type="number" class="form-control" id="rate" name="rate" min="0" step=".01" required/>
    </div>
    <div class="form-group">
        <input type="submit" value="{{ __('Create') }}" class="btn-create badge-blue">
        <input type="button" value="{{ __('Cancel') }}" class="btn-create bg-gray" data-dismiss="modal">
    </div>
</form>
