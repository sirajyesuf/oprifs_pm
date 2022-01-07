<form class="px-3" method="post" action="{{ route('invoices.update',[$currentWorkspace->slug,$invoice->id]) }}">
    @csrf
    <div class="row">
        <div class="form-group col-md-6">
            <label for="status" class="form-control-label">{{__('Status')}}</label>
            <select class="form-control select2" name="status" id="status">
                <option value="sent" @if($invoice->status == 'sent') selected @endif>{{__('Sent')}}</option>
                <option value="paid" @if($invoice->status == 'paid') selected @endif>{{__('Paid')}}</option>
                <option value="canceled" @if($invoice->status == 'canceled') selected @endif>{{__('Canceled')}}</option>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="discount" class="form-control-label">{{ __('Discount') }}</label>
            <div class="form-icon-user">
                <span class="currency-icon">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                <input class="form-control" type="number" min="0" id="discount" name="discount" value="{{$invoice->discount}}" placeholder="{{ __('Enter Discount') }}">
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="issue_date" class="form-control-label">{{ __('Issue Date') }}</label>
            <input class="form-control datepicker" type="text" id="issue_date" name="issue_date" value="{{$invoice->issue_date}}" autocomplete="off" required="required">
        </div>
        <div class="form-group col-md-6">
            <label for="due_date" class="form-control-label">{{__('Due Date')}}</label>
            <input class="form-control datepicker" type="text" id="due_date" name="due_date" value="{{$invoice->due_date}}" autocomplete="off" required="required">
        </div>
        <div class="form-group col-md-6">
            <label for="tax_id" class="form-control-label">{{__('Tax')}}%</label>
            <select class="form-control select2" name="tax_id" id="tax_id">
                <option value="">{{__('Select Tax')}}</option>
                @foreach($taxes as $p)
                    <option value="{{$p->id}}" @if($invoice->tax_id == $p->id) selected @endif>{{$p->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="client_id" class="form-control-label">{{__('Client')}}</label>
            <select class="form-control select2" name="client_id" id="client_id">
                <option value="">{{__('Select Client')}}</option>
                @foreach($clients as $p)
                    <option value="{{$p->id}}" @if($invoice->client_id == $p->id) selected @endif>{{$p->name}} - {{$p->email}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12">
            <input type="submit" class="btn-create badge-blue" value="{{ __('Save') }}">
            <input type="button" class="btn-create bg-gray" data-dismiss="modal" value="{{ __('Cancel') }}">
        </div>
    </div>
</form>
