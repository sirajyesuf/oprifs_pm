<form class="px-3" method="post" action="{{ route('invoices.store',[$currentWorkspace->slug]) }}">
    @csrf
    <div class="row">
        <div class="form-group col-md-6">
            <label for="project_id" class="form-control-label">{{__('Projects')}}</label>
            <select class="form-control select2" name="project_id" id="project_id">
                <option value="">{{__('Select Project')}}</option>
                @foreach($projects as $p)
                    <option value="{{$p->id}}">{{$p->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="discount" class="form-control-label">{{ __('Discount') }}</label>
            <div class="form-icon-user">
                <span class="currency-icon">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$' }}</span>
                <input class="form-control" type="number" min="0" id="discount" name="discount" placeholder="{{ __('Enter Discount') }}" value="0">
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="issue_date" class="form-control-label">{{ __('Issue Date') }}</label>
            <input class="form-control datepicker" type="text" id="issue_date" name="issue_date" autocomplete="off" required="required">
        </div>
        <div class="form-group col-md-6">
            <label for="due_date" class="form-control-label">{{__('Due Date')}}</label>
            <input class="form-control datepicker" type="text" id="due_date" name="due_date" autocomplete="off" required="required">
        </div>
        <div class="form-group col-md-6">
            <label for="tax_id" class="form-control-label">{{__('Tax')}}%</label>
            <select class="form-control select2" name="tax_id" id="tax_id">
                <option value="">{{__('Select Tax')}}</option>
                @foreach($taxes as $p)
                    <option value="{{$p->id}}">{{$p->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="client_id" class="form-control-label">{{__('Client')}}</label>
            <select class="form-control select2" name="client_id" id="client_id">
                <option value="">{{__('Select Client')}}</option>
                @foreach($clients as $p)
                    <option value="{{$p->id}}">{{$p->name}} - {{$p->email}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12">
            <input type="submit" class="btn-create badge-blue" value="{{ __('Save') }}">
            <input type="button" class="btn-create bg-gray" data-dismiss="modal" value="{{ __('Cancel') }}">
        </div>
    </div>
</form>
