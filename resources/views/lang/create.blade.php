<form class="px-3" method="post" action="{{ route('store_lang_workspace') }}">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <label for="code" class="form-control-label">{{ __('Language Code') }}</label>
            <input class="form-control" type="text" id="code" name="code" required="" placeholder="{{ __('Language Code') }}">
        </div>
        <div class="col-md-12">
            <input type="submit" value="{{ __('Save') }}" class="btn-create badge-blue">
            <input type="button" value="{{ __('Cancel') }}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
</form>
