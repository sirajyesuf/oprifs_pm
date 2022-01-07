@extends('layouts.admin')

@section('page-title') {{__('Invoices')}} @endsection

@section('action-button')
    @auth('web')
        @if($currentWorkspace->creater->id == Auth::user()->id)
            <div class="all-button-box mb-3">
                <a href="#" class="btn btn-xs btn-white btn-icon-only width-auto" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create New Invoice') }}" data-url="{{route('invoices.create',$currentWorkspace->slug)}}">
                    <i class="fa fa-plus"></i> {{ __('Create') }}
                </a>
            </div>
        @endif
    @endauth
@endsection

@section('content')

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover mb-0 animated" id="selection-datatable">
                                <thead>
                                <th>{{__('Invoice')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Issue Date')}}</th>
                                <th>{{__('Due Date')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__('Status')}}</th>
                                @auth('web')
                                    <th>{{__('Action')}}</th>
                                @endauth
                                </thead>
                                <tbody>
                                @foreach($invoices as $key => $invoice)
                                    <tr>
                                        <td class="Id sorting_1">
                                            <a href="@auth('web'){{ route('invoices.show',[$currentWorkspace->slug,$invoice->id]) }}@elseauth{{ route('client.invoices.show',[$currentWorkspace->slug,$invoice->id]) }}@endauth">
                                                {{App\Models\Utility::invoiceNumberFormat($invoice->invoice_id)}}
                                            </a>
                                        </td>
                                        <td>{{$invoice->project->name}}</td>
                                        <td>{{App\Models\Utility::dateFormat($invoice->issue_date)}}</td>
                                        <td>{{App\Models\Utility::dateFormat($invoice->due_date)}}</td>
                                        <td>{{$currentWorkspace->priceFormat($invoice->getTotal())}}</td>
                                        <td>
                                            @if($invoice->status == 'sent')
                                                <span class="badge badge-warning">{{__('Sent')}}</span>
                                            @elseif($invoice->status == 'paid')
                                                <span class="badge badge-success">{{__('Paid')}}</span>
                                            @elseif($invoice->status == 'canceled')
                                                <span class="badge badge-danger">{{__('Canceled')}}</span>
                                            @endif
                                        </td>
                                        @auth('web')
                                            <td class="text-right">
                                                <a href="#" class="edit-icon" data-url="{{ route('invoices.edit',[$currentWorkspace->slug,$invoice->id]) }}" data-ajax-popup="true" data-title="{{__('Edit Invoice')}}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <a href="#" class="delete-icon" data-confirm="{{ __('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="document.getElementById('delete-form-{{ $invoice->id }}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['invoices.destroy',[$currentWorkspace->slug,$invoice->id]],'id'=>'delete-form-'.$invoice->id]) !!}
                                                {!! Form::close() !!}
                                            </td>
                                        @endauth
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection
